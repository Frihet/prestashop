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



	public function getContent()
	{
		$output = '<h2>'.$this->displayName.'</h2>';
		if (Tools::isSubmit('submitOrderCarrierTollpost'))
		{
			Configuration::updateValue('ORDER_CARRIER_TOLLPOST_USERNAME', Tools::getValue('username'));
			Configuration::updateValue('ORDER_CARRIER_TOLLPOST_PASSWORD', Tools::getValue('password'));			
		}
		return $output.$this->displayForm();
	}

	public function displayForm()
	{
		return '
		<form action="'.$_SERVER['REQUEST_URI'].'" method="post">
			<fieldset>
				<legend>'.$this->l('Settings').'</legend>
				<label>'.$this->l('Username').'</label>
				<div class="margin-form">
					<input type="text" name="username" value="'.Configuration::get('ORDER_CARRIER_TOLLPOST_USERNAME').'" />
					<p class="clear">'.$this->l('Username for access to Tollposts API').'</p>
				</div>
				<label>'.$this->l('Password').'</label>
				<div class="margin-form">
					<input type="text" name="password" value="'.Configuration::get('ORDER_CARRIER_TOLLPOST_PASSWORD').'" />
					<p class="clear">'.$this->l('Password for access to Tollposts API').'</p>
				</div>
				<center><input type="submit" name="submitOrderCarrierTollpost" value="'.$this->l('Save').'" class="button" /></center>
			</fieldset>
		</form>';
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

		if ($params['carrier']->name != 'MyPack')
		        return '';


		$sql = "select pickup from `PREFIX_cart` where id_cart = {$cart->id}";
		$sql = str_replace('PREFIX_', _DB_PREFIX_, $sql);
		$row = Db::getInstance()->getRow($sql);
		if ($row) {
		    $smarty->assign('carrier_tollpost_pickup', $row['pickup']);
		}

		$cart_details = $cart->getSummaryDetails();
		$delivery = $cart_details['delivery'];


		if ($delivery->country == 'Norway') {
		    $street = $delivery->address1;
		    if ($delivery->address2 != "")
		      $street .= "," . $delivery->address2;
		    $zip = $delivery->postcode;
                // Espen Lyngaas added line to remove spaces in postcodes
                $zip = str_replace(" ","",$zip);


		    $servicepartnerXml = new DOMDocument();
		    $username = Configuration::get('ORDER_CARRIER_TOLLPOST_USERNAME');
		    $password = Configuration::get('ORDER_CARRIER_TOLLPOST_PASSWORD');
		    $servicepartnerXml->load("http://www.tollpost.no/rest.php?Object=servicepartnerproximity&Version=1&Action=get&username={$username}&password={$password}&DAddressCombined={$street}&DZipCode={$zip}");

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
		} else {
		    $pickups = array('auto' => $this->l('Your closest Tollpost service center'));
		}

		$smarty->assign(array('pickups' => $pickups));
		return $this->display(__FILE__, 'ordercarriertollpost.tpl');
		
        }
}

?>
