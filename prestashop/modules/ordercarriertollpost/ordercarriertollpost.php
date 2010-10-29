<?php

class OrderCarrierTollpost extends Module
{
	function __construct()
	{
		$this->name = 'ordercarriertollpost';
		$this->tab = 'carrier';
		$this->version = '0.9';

		parent::__construct();

		$this->displayName = $this->l('Tollpost globe');
		$this->description = $this->l('Tollpost globe options');
	}
	
	function install()
	{

		$sql = "ALTER TABLE `PREFIX_cart` ADD COLUMN (`pickup` text)";
		$sql = str_replace('PREFIX_', _DB_PREFIX_, $sql);
		try {
			Db::getInstance()->Execute(trim($sql));
		} catch (Exception $e) {}

		if (   parent::install() == false
		    OR $this->registerHook('extraCarrierDetailsProcess') == false
		    OR $this->registerHook('extraCarrierDetails') == false)
			return false;
		return true;
	}

	public function hookExtraCarrierDetailsProcess($params)
        {
		global $cart, $smarty, $errors, $isVirtualCart, $orderTotal;

		if ($params['carrier']->name != 'Tollpost')
		        return '';

		$pickup = pSQL($_POST['carrier_tollpost_pickup']);

		$sql = "update `PREFIX_cart` set pickup = '{$pickup}' where id_cart = {$cart->id}";
		$sql = str_replace('PREFIX_', _DB_PREFIX_, $sql);
		Db::getInstance()->Execute(trim($sql));
        }

	public function hookExtraCarrierDetails($params)
        {
		global $cart, $smarty, $errors, $isVirtualCart, $orderTotal;

		if ($params['carrier']->name != 'Tollpost')
		        return '';


		$sql = "select pickup from `PREFIX_cart` where id_cart = {$cart->id}";
		$sql = str_replace('PREFIX_', _DB_PREFIX_, $sql);
		$row = Db::getInstance()->getRow($sql);
		if ($row) {
		    $smarty->assign('carrier_tollpost_pickup', $row['pickup']);
		}

		$cart_details = $cart->getSummaryDetails();
		$delivery = $cart_details['delivery'];

		$street = $delivery->address1;
		if ($delivery->address2 != "")
                  $street .= "," . $delivery->address2;
		$zip = $delivery->postcode;

		$servicepartnerXml = new DOMDocument();
		$servicepartnerXml->load("http://www.tollpost.no/rest.php?Object=servicepartnerproximity&Version=1&Action=get&username=XXXX@YYYY.no&password=SECRET&DAddressCombined={$street}&DZipCode={$zip}");

		$xpath = new DOMXPath($servicepartnerXml);
		$servicepartners = $xpath->query('//TollpostServicepartner');

		$pickups = array();
		foreach ($servicepartners as $servicepartner) {
		    $partner_id = $xpath->query('ServicepartnerID', $servicepartner); $partner_id = $partner_id->item(0)->textContent;
		    $name = $xpath->query('CompanyName1', $servicepartner); $name = $name->item(0)->textContent;
		    $street = $xpath->query('StreetAddress1', $servicepartner); $street = $street->item(0)->textContent;
		    $zip = $xpath->query('PostalCode', $servicepartner); $zip = $zip->item(0)->textContent;
		    $city = $xpath->query('City', $servicepartner); $city = $city->item(0)->textContent;

		    $pickups[$partner_id] = "$name ($street, $zip $city)";
	        }

		$smarty->assign(array('pickups' => $pickups));
		return $this->display(__FILE__, 'selectpickup.tpl');
		
        }
}

?>
