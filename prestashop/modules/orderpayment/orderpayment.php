<?php

class OrderPayment extends OrderPage
{
	function __construct()
	{
		$this->name = 'orderpayment';
		$this->tab = 'Order';
		$this->version = '0.9';

		parent::__construct();

		$this->orderPageTitle = $this->l("Payment");
		$this->displayName = $this->l('Payment form');
		$this->description = $this->l('Payment and order finalisation');
	}

	/* Display payment form */
	function displayOrderStep($params)
	{
		global $smarty, $cart, $currency, $cookie, $orderTotal;

		if ($cart->getOrderTotalLC() <= 0)
		{
			$order = new FreeOrder();
			$order->validateOrder(intval($cart->id), _PS_OS_PAYMENT_, 0, Tools::displayError('Free order', false));
			Tools::redirect('history.php');
		}


		// Redirect instead of displaying payment modules if any module are grafted on
		Hook::backBeforePayment(strval(Tools::getValue('back')));

		/* We may need to display an order summary */
		$smarty->assign($cart->getSummaryDetails());

		$cookie->checkedTOS = '1';
		$smarty->assign(array('HOOK_PAYMENT' => Module::hookExecPayment(), 'total_price' => floatval($orderTotal)));

		Tools::safePostVars();
		include_once(dirname(__FILE__).'/../../header.php');
		echo $this->display(__FILE__, 'orderpayment.tpl');
	}
}


?>
