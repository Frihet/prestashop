<?php

class OrderSummary extends OrderPage
{
	function __construct()
	{
		$this->name = 'ordersummary';
		$this->tab = 'Order';
		$this->version = '0.9';

		parent::__construct();

		$this->orderPageTitle = $this->l('Summary');
		$this->displayName = $this->l('Brief order summary');
		$this->description = $this->l('Shows a brief summary of the order');
	}

	function processOrderStep($params)
       	{
		global $errors, $cart, $smarty, $order_pages_hook_stay;

		/* Manage discounts */
		if (intval(Tools::getValue('addDiscount')) == 1)
		{
			$discountName = Tools::getValue('discount_name');
			if (!Validate::isDiscountName($discountName))
				$errors[] = Tools::displayError('voucher name not valid');
			else
			{
				$discount = new Discount(intval(Discount::getIdByName($discountName)));
				if (is_object($discount) AND $discount->id)
				{
					if ($tmpError = $cart->checkDiscountValidity($discount, $cart->getDiscounts(), $cart->getOrderTotalLC(), $cart->getProducts(), true))
						$errors[] = $tmpError;
				}
				else
					$errors[] = Tools::displayError('voucher name not valid');
				if (!sizeof($errors))
				{
					$cart->addDiscount(intval($discount->id));
				}
			}

			// Why do we need a redirect here? Copied from
                        // original order.php source. Seems like
                        // otherwize the cart display doesn't get
                        // updated properly
			Tools::redirect('order.php?step=' . $params['step']);
		}
		elseif (intval(Tools::getValue('deleteDiscount')) == 1)
		{
			if (Validate::isUnsignedId($_GET['deleteDiscount']))
				$cart->deleteDiscount(intval($_GET['deleteDiscount']));
			Tools::redirect('order.php?step=' . $params['step']);
		}
        }

	/* Display order summary */
	function displayOrderStep($params)
	{
		global $smarty, $cart, $errors;

                $smarty->assign('errors', $errors);

		if (file_exists(_PS_SHIP_IMG_DIR_.intval($cart->id_carrier).'.jpg'))
			$smarty->assign('carrierPicture', 1);

		$cart->save(); // Hack to get loyalty and other modules happy
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
		include_once(dirname(__FILE__).'/../../header.php');
		echo $this->display(__FILE__, 'ordersummary.tpl');
	}
}


?>
