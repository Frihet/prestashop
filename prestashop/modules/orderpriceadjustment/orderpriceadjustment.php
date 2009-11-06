<?php

class OrderPriceAdjustment extends OrderPage
{
	function __construct()
	{
		$this->name = 'orderpriceadjustment';
		$this->tab = 'Order';
		$this->version = '0.9';

		parent::__construct();

		$this->orderPageTitle = $this->l("Price adjustment");
		$this->displayName = $this->l('Price adjustment summary');
		$this->description = $this->l('Presents the user with a second order summary with prices in the local currency and with all group-specific discounts applied');
	}

	function install()
	{
			if
			(
				parent::install() == false
			)
			return false;
		return true;
	}

	function processOrderStep($params)
       	{
		global $cart, $errors;

        }

	function displayOrderStep($params)
	{
		global $smarty, $cart, $errors, $currency;

		$smarty->assign("currency_iso_code", $currency->iso_code);
		$smarty->assign("currency_name", $currency->name);

                $smarty->assign('errors', $errors);
               	$smarty->assign('mdl_dir', dirname(__FILE__) . '/');

		if (file_exists(_PS_SHIP_IMG_DIR_.intval($cart->id_carrier).'.jpg'))
			$smarty->assign('carrierPicture', 1);
		$summary = $cart->getSummaryDetails();
		$customizedDatas = Product::getAllCustomizedDatas(intval($cart->id));
		Product::addCustomizationPrice($summary['products'], $customizedDatas);

		if ($free_ship = intval(Configuration::get('PS_SHIPPING_FREE_PRICE')))
		{
			$discounts = $cart->getDiscounts();
			$total_free_ship =  $free_ship - ($summary['total_products_wt'] + $summary['total_discounts']);
			foreach ($discounts as $discount)
				if ($discount['id_discount_type'] == 3)
				{
					$total_free_ship = 0;
					break ;
				}
			$smarty->assign('free_ship', $total_free_ship);
		}
		$smarty->assign($summary);
		$token = Tools::getToken(false);
		$smarty->assign(array(
			'token_cart' => $token,
			'voucherAllowed' => Configuration::get('PS_VOUCHERS'),
			'HOOK_SHOPPING_CART' => Module::hookExec('shoppingCart', $summary),
			'HOOK_SHOPPING_CART_EXTRA' => Module::hookExec('shoppingCartExtra', $summary),
			'shippingCost' => $cart->getOrderTotalLC(true, 5),
			'shippingCostTaxExc' => $cart->getOrderTotalLC(false, 5),
			'customizedDatas' => $customizedDatas,
			'CUSTOMIZE_FILE' => _CUSTOMIZE_FILE_,
			'CUSTOMIZE_TEXTFIELD' => _CUSTOMIZE_TEXTFIELD_,
			'lastProductAdded' => $cart->getLastProduct()
			));
		Tools::safePostVars();

		if ($oldMessage = Message::getMessageByCartId(intval($cart->id)))
			$smarty->assign('oldMessage', $oldMessage['message']);
		$smarty->assign('cart', $cart);
		$smarty->assign('back', strval(Tools::getValue('back')));

		Tools::safePostVars();
		include_once(dirname(__FILE__).'/../../header.php');
		$smarty->display(dirname(__FILE__).'/module-order-priceadjustment.tpl');
	}
}


?>
