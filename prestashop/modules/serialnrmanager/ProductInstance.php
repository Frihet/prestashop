<?php

/**
  * ProductInstance class, ProductInstance.php
  * Product instance management
  * @category classes
  *
  * @author Egil Möller <egil.moller@freecode.no>
  * @copyright PrestaShop, FreeCode AS
  * @license http://www.opensource.org/licenses/osl-3.0.php Open-source licence 3.0
  * @version 0.9
  *
  */

class		ProductInstance extends ObjectModel
{
	public		$id;

	/** @var string Serial number */
	public 		$serial;

	/** @var integer Customer ID */
	public 		$id_current_owner;

	/** @var integer Order detail ID */
	public 		$id_order_detail;

	/** @var string Object creation date */
	public 		$date_add;

	/** @var string Object last modification date */
	public 		$date_upd;
	
	protected	$fieldsRequired = array();
	protected	$fieldsValidate = array('id_order_detail' => 'isUnsignedId', 'id_current_owner' => 'isUnsignedId');

	protected 	$table = 'product_instance';
	protected 	$identifier = 'id_product_instance';

	public function getFields()
	{
		parent::validateFields();

		$fields = array();
		$fields['serial'] = $this->serial;
		$fields['id_product_instance'] = intval($this->id);
		$fields['id_order_detail'] = intval($this->id_order_detail);
		$fields['id_current_owner'] = intval($this->id_current_owner);
		$fields['date_add'] = pSQL($this->date_add);
		$fields['date_upd'] = pSQL($this->date_upd);

		var_dump($fields);
		return $fields;
	}

	public function add($autodate = true, $nullValues = false)
	{
		$return = parent::add($autodate);
		Module::hookExec('productinstance');
		return $return;
	}

	public function update($nullValues = false)
	{
		$return = parent::update();
		Module::hookExec('productinstance');
		return $return;
	}

	public function delete()
	{
		if (!Db::getInstance()->Execute('DELETE FROM `'._DB_PREFIX_.'product_instance` WHERE `id_product_instance` = '.intval($this->id)))
			return false;
		return parent::delete();
	}

	static public function search($serial, $id_lang)
	{
		$sql = "
		 SELECT
                   pi.*,
                   pl.name AS product_name,
                   o.invoice_date AS order_invoice_date,
                   cu.firstname as current_owner_firstname,
                   cu.lastname as current_owner_lastname,
                   cu.email as current_owner_email,
                   v.title as vendor_title
		 FROM
                  `PREFIX_product_instance` AS pi
                  JOIN `PREFIX_order_detail` AS od ON
                   pi.id_order_detail = od.id_order_detail
                  JOIN `PREFIX_product_lang` AS pl ON
                   od.product_id = pl.id_product
                   AND pl.id_lang = '{$id_lang}'
                  JOIN `PREFIX_orders` AS o ON
                   od.id_order = o.id_order
                  JOIN `PREFIX_cart` AS c ON
                   o.id_cart = c.id_cart
                  JOIN `PREFIX_cart_product` AS cp ON
                   cp.id_cart = c.id_cart
                   AND cp.id_product = od.product_id
                   AND cp.id_product_attribute = od.product_attribute_id
                  JOIN `PREFIX_vendor` AS v ON
                   v.id_vendor = cp.id_vendor
                  LEFT OUTER JOIN `PREFIX_customer` AS cu ON
                   pi.id_current_owner = cu.id_customer
		 WHERE `serial` LIKE '%{$serial}%'
		 ORDER BY date_add ASC
                ";
		$sql = str_replace('PREFIX_', _DB_PREFIX_, $sql);
		return Db::getInstance()->ExecuteS($sql);
	}
}
