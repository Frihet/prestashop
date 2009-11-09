<?php

/**
  * Vendor class, Vendor.php
  * @category classes
  *
  * @author Egil Möller <egil.moller@freecode.no>
  * @copyright FreeCode AS
  * @license http://www.opensource.org/licenses/osl-3.0.php Open-source licence 3.0
  * @license http://www.fsf.org/licensing/licenses/gpl.html GPL 3.0+
  * @version 0.1
  *
  */

class Vendor
{
	static public function currentVendor() {
		global $cart;

		if (!$cart OR !$cart->id_guest)
			return;

		$guest = new Guest($cart->id_guest);
		if (!$guest->id_customer)
			return;
		$id_customer = intval($guest->id_customer);

		$sql = "select id_vendor from PREFIX_vendor where id_customer = {$id_customer}";
		$sql = str_replace('PREFIX_', _DB_PREFIX_, $sql);
		$row = Db::getInstance()->getRow($sql);
		if ($row == null)
		   	 return null;
		return $row['id_vendor'];
	}
}
