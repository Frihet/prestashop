<?php

class OrderVendor extends OrderPage
{
	function __construct()
	{
		$this->name = 'ordervendor';
		$this->tab = 'Order';
		$this->version = '0.9';

		parent::__construct();

		$this->orderPageTitle = $this->l("Vendor");
		$this->displayName = $this->l('Vendor chooser');
		$this->description = $this->l('Lets the user choose a supporting local vendor for each product');
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
				if (trim($query))
					Db::getInstance()->Execute(trim($query));

			$sql = "ALTER TABLE `PREFIX_cart_product` ADD COLUMN (`id_vendor` int(10) unsigned NOT NULL, FOREIGN KEY (`id_vendor`) REFERENCES `PREFIX_vendor`(`id_vendor`))";
			$sql = str_replace('PREFIX_', _DB_PREFIX_, $sql);
			try {
				Db::getInstance()->Execute(trim($sql));
			} catch (Exception $e) {}

			$sql = "DELETE FROM `PREFIX_postcode`";
			$sql = str_replace('PREFIX_', _DB_PREFIX_, $sql);
			Db::getInstance()->Execute(trim($sql));

			foreach (glob(dirname(__FILE__) . '/postcode_coordinates.*.csv') as $filename) {
				$parts = explode('.', $filename);
				$iso_code = $parts[count($parts)-2];

				$query = 'select `id_country` from PREFIX_country where iso_code = "' . pSql($iso_code) . '"';
				$query = str_replace('PREFIX_', _DB_PREFIX_, $query);
				$result = Db::getInstance()->GetRow($query);
				$id_country = $result['id_country'];

				$file = fopen($filename, "r");
				$header = fgetcsv($file, 0, "\t");
				while ($line = fgetcsv($file, 0, "\t")) {
					echo "TRALALA<br>";
					$line = array_combine($header, $line);
					$query = 'INSERT INTO `PREFIX_postcode` (`id_country`, `name`, `longitude`, `latitude`) VALUES (' .
						pSql($id_country) . ', ' .
						'"' . pSql($line['Postcode']) . '", "' .
						pSql($line['Longitude']) . '", "' .
						pSql($line['Latitude']) . '")';
					$query = str_replace('PREFIX_', _DB_PREFIX_, $query);
					Db::getInstance()->Execute($query);
                                }
			}

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

		require_once(dirname(__FILE__)."/OrderProductVendor.php");

		foreach ($cart->getProducts() as $product_line) {
	 		OrderProductVendor::setVendor($cart, $product_line['id_product'], intval(Tools::getValue('vendor' . $product_line['id_product'])));
		}
        }

	function displayOrderStep($params)
	{
		global $smarty, $cart, $errors;

		$smarty->assign("product_lines", OrderProductVendor::getProductsWithVendors($cart));

		if ($oldMessage = Message::getMessageByCartId(intval($cart->id)))
			$smarty->assign('oldMessage', $oldMessage['message']);
		$smarty->assign('cart', $cart);
		$smarty->assign('back', strval(Tools::getValue('back')));

		Tools::safePostVars();
		include_once(dirname(__FILE__).'/../../header.php');
		$smarty->display(dirname(__FILE__).'/module-order-vendor.tpl');
	}
}


?>
