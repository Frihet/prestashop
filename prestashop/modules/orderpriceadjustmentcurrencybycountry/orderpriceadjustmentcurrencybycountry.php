<?php

class OrderPriceAdjustmentCurrencyByCountry extends Module
{
	function __construct()
	{
		$this->name = 'orderpriceadjustmentcurrencybycountry';
		$this->tab = 'Order price adjustment';
		$this->version = '0.1';

		parent::__construct();

		$this->displayName = $this->l('Currency by country');
		$this->description = $this->l('Forces a switch to the currency used in the country specified in the billing address');
	}
	
	function install()
	{
		$sql = "ALTER TABLE `PREFIX_country` ADD COLUMN (`id_currency` int(10) unsigned NOT NULL, FOREIGN KEY (`id_currency`) REFERENCES `PREFIX_currency`(`id_currency`))";
		$sql = str_replace('PREFIX_', _DB_PREFIX_, $sql);
		try {
			Db::getInstance()->Execute(trim($sql));
		} catch (Exception $e) {}

		if
		(
			parent::install() == false
			OR $this->registerHook('orderPriceAdjustment') == false
		)
			return false;
		return true;
	}

	public function hookOrderPriceAdjustment($params)
        {
		global $cart, $cookie;

		$invoice_address = new Address($cart->id_address_invoice);

		$sql = "select id_currency from `PREFIX_country` where id_country = {$invoice_address->id_country}";
		$sql = str_replace('PREFIX_', _DB_PREFIX_, $sql);
		$row = Db::getInstance()->getRow($sql);

		$country_id_currency = intval($row['id_currency']);
		if ($country_id_currency != 0 AND $country_id_currency != $cookie->id_currency) {
			$cookie->id_currency = $country_id_currency;
			Tools::redirect($_SERVER['REQUEST_URI'], '');
		}
        }
}

?>
