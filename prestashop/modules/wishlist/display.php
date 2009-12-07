<?php

include_once(dirname(__FILE__).'/../../config/config.inc.php');
include_once(dirname(__FILE__).'/../../init.php');

require_once(dirname(__FILE__).'/../../classes/Product.php');
require_once(dirname(__FILE__).'/wishlist.php');


function getHashFromCart($cart)
{
	return md5(_EXPORT_URL_KEY_.$cart->id_customer.$cart->id);
}

function getCartFromHash($hash)
{
	$hash = pSQL($hash);
	$key = _EXPORT_URL_KEY_;
	$sql = "
	 select id_cart from PREFIX_cart where md5(concat('{$key}', id_customer, id_cart)) = '{$hash}'
	";
	$sql = str_replace('PREFIX_', _DB_PREFIX_, $sql);
	$row = Db::getInstance()->getRow($sql);
	return new Cart($row['id_cart']);
}

if (!isset($_GET['id'])) {
   	Tools::redirect('modules/wishlist/display.php?id=' . getHashFromCart($cart));
}
$wishlist_cart = getCartFromHash($_GET['id']);


function ensureCart() {
        global $cart, $cookie;

	/* Product addition to the cart */
	if (!isset($cart->id) OR !$cart->id)
	{
		$cart->id_address_delivery = intval($cookie->id_address_delivery);
		$cart->id_address_invoice = intval($cookie->id_address_invoice);
	    $cart->add();
	    if ($cart->id)
			$cookie->id_cart = intval($cart->id);
	}
}

function copyQty($src_cart, $dst_cart, $id_product = false, $id_customization = false) {
	if ($id_product == false) {
	        if ($id_customization == false) {
			$sql = "
			  select id_cart, id_product, id_product_attribute, quantity, id_vendor
			  from PREFIX_cart_product
			  where id_cart = '{$src_cart->id}'
			 ";
			 $sql = str_replace('PREFIX_', _DB_PREFIX_, $sql);
			 $result = Db::getInstance()->ExecuteS($sql);
			 foreach ($result as $row) {
				 copyQty($src_cart, $dst_cart, $row['id_product'], false);
			 }
			 return ;
		} else {
		        $sql = "
			 select id_customization, id_product_attribute, id_cart, id_product, quantity, quantity_refunded, quantity_returned
			 from PREFIX_customization
			 where id_customization = '{$id_customization}'
			";
			$sql = str_replace('PREFIX_', _DB_PREFIX_, $sql);
			$row = Db::getInstance()->getRow($sql);   
			$id_product = $row['id_product'];
		}
	}

	$sql = "
         select id_cart, id_product, id_product_attribute, quantity, id_vendor
         from PREFIX_cart_product
	 where id_cart = {$src_cart->id} and id_product = {$id_product}
        ";
	$sql = str_replace('PREFIX_', _DB_PREFIX_, $sql);
	$row = Db::getInstance()->getRow($sql);

	$id_product_attribute = $row['id_product_attribute'];
	$quantity = $row['quantity'];

	if ($id_customization == false) {
	        $sql = "
		 select id_customization, id_product_attribute, id_cart, id_product, quantity, quantity_refunded, quantity_returned
		 from PREFIX_customization
                 where id_product = '{$id_product}' and id_cart = '{$src_cart->id}' and id_product_attribute = '{$id_product_attribute}'
		";
		$sql = str_replace('PREFIX_', _DB_PREFIX_, $sql);
		$result = Db::getInstance()->ExecuteS($sql);	        
		if (count($result) > 0) {
			foreach ($result as $row) {
				copyQty($src_cart, $dst_cart, $id_product, $row['id_customization']);
			}
			return ;
		}
	}

	$dst_cart->deleteCustomizationInformations($id_product);
	if ($id_customization != false) {
		$sql = "
		 select id_customization, id_product_attribute, id_cart, id_product, quantity, quantity_refunded, quantity_returned
		 from PREFIX_customization
                 where id_customization = '{$id_customization}'
		";
		$sql = str_replace('PREFIX_', _DB_PREFIX_, $sql);
		$row = Db::getInstance()->getRow($sql);
		$quantity = $row['quantity'];

		$sql = "
		 select id_customization, type, `index`, value
		 from PREFIX_customized_data
                 where id_customization = '{$id_customization}'
		";
		$sql = str_replace('PREFIX_', _DB_PREFIX_, $sql);
		$result = Db::getInstance()->ExecuteS($sql);	        
		foreach ($result as $row) {
			if ($row['type'] == _CUSTOMIZE_FILE_) {
			 	$dst_cart->addPictureToProduct($id_product, $row['index'], $row['value']);
			} else if ($row['type'] == _CUSTOMIZE_TEXTFIELD_) {
			        $dst_cart->addTextFieldToProduct($id_product, $row['index'], $row['value']);
			} else if ($row['type'] == _CUSTOMIZE_SCHEDULE_) {
			        $dst_cart->setScheduleFieldToProduct($id_product, $row['index'], $row['value']);
			} else { die("INTERNAL ERROR ARGH ARGH: Unknown customization type:" .$row['type'] ); }
		}
        }
 	$dst_cart->updateQty($quantity, $id_product, $id_product_attribute);
}


ensureCart();
if (Tools::isSubmit("addCustomizationToCart")) {
        copyQty($wishlist_cart, $cart, false, $_POST['addCustomizationToCart']);
        Tools::redirect('modules/wishlist/display.php?id=' . getHashFromCart($wishlist_cart));
} else if (Tools::isSubmit("addProductToCart")) {
        copyQty($wishlist_cart, $cart, $_POST['addProductToCart']);
        Tools::redirect('modules/wishlist/display.php?id=' . getHashFromCart($wishlist_cart));
} else if (Tools::isSubmit("addAllToCart")) {
        copyQty($wishlist_cart, $cart);
        Tools::redirect('modules/wishlist/display.php?id=' . getHashFromCart($wishlist_cart));
}


$errors = array();

$smarty->assign('errors', $errors);
$smarty->assign('mdl_dir', dirname(__FILE__) . '/');

$smarty->assign("currency_iso_code", $currency->iso_code);
$smarty->assign("currency_name", $currency->name);


$smarty->assign('cart_hash_id', $_GET['id']);
if (!$wishlist_cart->nbProducts()) {
	$smarty->assign('empty', 1);
}
if (file_exists(_PS_SHIP_IMG_DIR_.intval($wishlist_cart->id_carrier).'.jpg'))
	$smarty->assign('carrierPicture', 1);

$summary = $wishlist_cart->getSummaryDetails();
$customizedDatas = Product::getAllCustomizedDatas(intval($wishlist_cart->id));
Product::addCustomizationPrice($summary['products'], $customizedDatas);

$smarty->assign($summary);
$smarty->assign(array(
	'customizedDatas' => $customizedDatas,
	'CUSTOMIZE_FILE' => _CUSTOMIZE_FILE_,
	'CUSTOMIZE_TEXTFIELD' => _CUSTOMIZE_TEXTFIELD_,
	'lastProductAdded' => $wishlist_cart->getLastProduct()
	));
$smarty->assign('cart', $wishlist_cart);


Tools::safePostVars();
include_once(dirname(__FILE__).'/../../header.php');

$lst = new WishList();
echo $lst->display(dirname(__FILE__).'/wishlist.php', 'wishlist.tpl');

include(dirname(__FILE__).'/../../footer.php');

?>
