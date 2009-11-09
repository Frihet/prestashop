<?php

class SerialNrManager extends Module
{
	function __construct()
	{
		$this->name = 'serialnrmanager';
		$this->tab = 'Vendor';
		$this->version = '0.9';

		parent::__construct();

		$this->displayName = $this->l('Serial number management');
		$this->description = $this->l('Tool for vendors to manage their serial numbers and guarantee cards');
	}
	
	function install()
	{
			if (!file_exists(dirname(__FILE__).'/install.sql'))
				return (false);
			else if (!$sql = file_get_contents(dirname(__FILE__).'/install.sql'))
				return (false);
			$sql = str_replace('PREFIX_', _DB_PREFIX_, $sql);
			$sql = preg_split("/;\s*[\r\n]+/",$sql);
			foreach ($sql as $query)
				if (trim($query) && !Db::getInstance()->Execute(trim($query)))
					return (false);
			if
			(
				parent::install() == false
				OR $this->registerHook('myAccountBlock') == false
				OR $this->registerHook('customerAccount') == false
			)
			return false;
		return true;
	}

	public function hookMyAccountBlock($params)
        {
		global $smarty, $cart;

		if (!$cart OR !$cart->id_guest)
			return;

		$guest = new Guest($cart->id_guest);
		if (!$guest->id_customer)
			return;
		$id_customer = intval($guest->id_customer);

		$sql = "select id_vendor from PREFIX_vendor where id_customer = {$id_customer}";
		$sql = str_replace('PREFIX_', _DB_PREFIX_, $sql);
		if (Db::getInstance()->getRow($sql) != null)
			return $this->display(__FILE__, 'menuitems.tpl');
        }

	public function hookCustomerAccount($params)
        {
		global $smarty, $cart;

		if (!$cart OR !$cart->id_guest)
			return;

		$guest = new Guest($cart->id_guest);
		if (!$guest->id_customer)
			return;
		$id_customer = intval($guest->id_customer);

		$sql = "select id_vendor from PREFIX_vendor where id_customer = {$id_customer}";
		$sql = str_replace('PREFIX_', _DB_PREFIX_, $sql);
		if (Db::getInstance()->getRow($sql) != null)
			return $this->display(__FILE__, 'menuitems.tpl');
        }
}

?>
