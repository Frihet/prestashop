<?php

include_once(dirname(__FILE__).'/../../config/config.inc.php');
include_once(dirname(__FILE__).'/../../init.php');


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

function copyCartProduct($dst_cart_id, $where = 'true', $join = '') {
	$sql = "
	 insert into
          PREFIX_cart_product (id_cart, id_product, id_product_attribute, quantity, id_vendor)
         select
	  {$dst_cart_id},
	  PREFIX_cart_product.id_product,
	  PREFIX_cart_product.id_product_attribute,
	  PREFIX_cart_product.quantity,
	  PREFIX_cart_product.id_vendor
	 from 
	  PREFIX_cart_product
	  {$join}
	 where
	  {$where}
        ";
	$sql = str_replace('PREFIX_', _DB_PREFIX_, $sql);
	Db::getInstance()->Execute($sql);
}

function copyCustomization($dst_cart_id, $where = 'true', $join = '') {
	copyCartProduct($dst_cart_id,
         $where,
         "left outer join PREFIX_customization on
           PREFIX_cart_product.id_cart = PREFIX_customization.id_cart
           and PREFIX_cart_product.id_product = PREFIX_customization.id_product
         ");

	$sql = "
	 insert into
          PREFIX_customization (id_product_attribute, id_cart, id_product, quantity, quantity_refunded, quantity_returned)
         select
	  PREFIX_customization.id_product_attribute,
	  {$dst_cart_id},
	  PREFIX_customization.id_product,
	  PREFIX_customization.quantity,
	  PREFIX_customization.quantity_refunded,
	  PREFIX_customization.quantity_returned
	 from 
          PREFIX_cart_product
	  join PREFIX_customization on
           PREFIX_cart_product.id_cart = PREFIX_customization.id_cart
           and PREFIX_cart_product.id_product = PREFIX_customization.id_product
	 where
	  {$where}
        ";
	$sql = str_replace('PREFIX_', _DB_PREFIX_, $sql);
	Db::getInstance()->Execute($sql);

	$row = Db::getInstance()->getRow("select last_insert_id() as id");
	$id_customization = $row['id'];

	$sql = "
	 insert into
          PREFIX_customized_data (id_customization, `type`, `index`, value)
         select
	  {$id_customization},
	  PREFIX_customized_data.type,
	  PREFIX_customized_data.index,
	  PREFIX_customized_data.value
	 from 
          PREFIX_cart_product
	  join PREFIX_customization on
           PREFIX_cart_product.id_cart = PREFIX_customization.id_cart
           and PREFIX_cart_product.id_product = PREFIX_customization.id_product
	  join PREFIX_customized_data on
	   PREFIX_customization.id_customization = PREFIX_customized_data.id_customization
	 where
	  {$where}
        ";
	$sql = str_replace('PREFIX_', _DB_PREFIX_, $sql);
	Db::getInstance()->Execute($sql);

	$sql = "
	 insert into
          PREFIX_customized_data_schedule_booking (id_customization, id_customization_field, id_customization_field_schedule)
         select
	  {$id_customization},
	  PREFIX_customized_data_schedule_booking.id_customization_field,
	  PREFIX_customized_data_schedule_booking.id_customization_field_schedule
	 from 
          PREFIX_cart_product
	  join PREFIX_customization on
           PREFIX_cart_product.id_cart = PREFIX_customization.id_cart
           and PREFIX_cart_product.id_product = PREFIX_customization.id_product
	  join PREFIX_customized_data_schedule_booking on
	   PREFIX_customization.id_customization = PREFIX_customized_data_schedule_booking.id_customization
	 where
	  {$where}
        ";
	$sql = str_replace('PREFIX_', _DB_PREFIX_, $sql);
	Db::getInstance()->Execute($sql);
}

if (Tools::isSubmit("addCustomizationToCart")) {
        copyCustomization($cart->id, "PREFIX_cart_product.id_cart = {$wishlist_cart->id} and PREFIX_customization.id_customization = {$_POST['addCustomizationToCart']}");
        Tools::redirect('modules/wishlist/display.php?id=' . getHashFromCart($wishlist_cart));
} else if (Tools::isSubmit("addProductToCart")) {
        copyCustomization($cart->id, "PREFIX_cart_product.id_cart = {$wishlist_cart->id} and PREFIX_cart_product.id_product = {$_POST['addProductToCart']}");
        Tools::redirect('modules/wishlist/display.php?id=' . getHashFromCart($wishlist_cart));
} else if (Tools::isSubmit("addAllToCart")) {
        copyCustomization($cart->id, "PREFIX_cart_product.id_cart = {$wishlist_cart->id}");
        Tools::edirect('modules/wishlist/display.php?id=' . getHashFromCart($wishlist_cart));
} else if (0) {
        ensureCart();
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
$smarty->display(dirname(__FILE__).'/wishlist.tpl');

include(dirname(__FILE__).'/../../footer.php');

?>
