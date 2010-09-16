<?php

class OrderAddressSameCountry extends Module
{
	function __construct()
	{
		$this->name = 'orderaddresssamecountry';
		$this->tab = 'Order address verification';
		$this->version = '0.1';

		parent::__construct();

		$this->displayName = $this->l('Same country for billing & delivery');
		$this->description = $this->l('Requires that the billing and delivery addresses are in the same country');
	}
	
	function install()
	{
		if
		(
			parent::install() == false
			OR $this->registerHook('orderAddressVerification') == false
		)
			return false;
		return true;
	}

	public function hookOrderAddressVerification($params)
        {
		global $cart, $cookie, $errors;

		$delivery = new Address(intval($cart->id_address_delivery));
		$invoice = new Address(intval($cart->id_address_invoice));

		if ($delivery->country != $invoice->country) {
		        $errors[] = 'Delivery and billing addresses must reside in the same country';
		}
        }
}

?>
