<?php

/**
  * Cart class, Cart.php
  * Carts management
  * @category classes
  *
  * @author PrestaShop <support@prestashop.com>
  * @author Egil Möller <egil.moller@freecode.no>
  * @copyright PrestaShop, FreeCode AS
  * @license http://www.opensource.org/licenses/osl-3.0.php Open-source licence 3.0
  * @version 1.2
  *
  */

class		CompareCart extends ObjectModel
{
	public		$id;

	/** @var integer Customer ID */
	public 		$id_customer;

	/** @var integer Guest ID */
	public 		$id_guest;

	/** @var integer Language ID */
	public 		$id_lang;

	/** @var string Object creation date */
	public 		$date_add;

	/** @var string Object last modification date */
	public 		$date_upd;
	
	protected	$fieldsRequired = array();
	protected	$fieldsValidate = array('id_customer' => 'isUnsignedId', 'id_guest' => 'isUnsignedId');

	private		$_products = NULL;
	protected 	$table = 'compare_cart';
	protected 	$identifier = 'id_compare_cart';

	public function getFields()
	{
		parent::validateFields();

		$fields['id_customer'] = intval($this->id_customer);
		$fields['id_guest'] = intval($this->id_guest);
		$fields['date_add'] = pSQL($this->date_add);
		$fields['date_upd'] = pSQL($this->date_upd);

		return $fields;
	}

	public function add($autodate = true, $nullValues = false)
	{
		$return = parent::add($autodate);
		Module::hookExec('comparecart');
		return $return;
	}

	public function update($nullValues = false)
	{
		$return = parent::update();
		Module::hookExec('comparecart');
		return $return;
	}

	public function delete()
	{
		if (!Db::getInstance()->Execute('DELETE FROM `'._DB_PREFIX_.'compare_cart_product` WHERE `id_compare_cart` = '.intval($this->id)))
			return false;
		return parent::delete();
	}

	public function getLastProduct()
	{
		$sql = '
			SELECT `id_product`, `id_product_attribute`
			FROM `'._DB_PREFIX_.'compare_cart_product`
			WHERE `id_compare_cart` = '.intval($this->id).'
			ORDER BY `date_add` DESC';
		$result = Db::getInstance()->GetRow($sql);
		if ($result AND isset($result['id_product']) AND $result['id_product'])
			return $result;
		return false;
	}

	/**
	 * Return cart products
	 *
	 * @result array Products
	 */
	public function getProducts($refresh = false, $id_product = false)
	{
		if (!$this->id)
			return array();
		if ($this->_products AND !$refresh)
			return $this->_products;
		$sql = '
		SELECT `id_product`
		FROM `'._DB_PREFIX_.'compare_cart_product` cp
		WHERE `id_compare_cart` = '.intval($this->id).'
		'.($id_product ? ' AND `id_product` = '.intval($id_product) : '').'
		AND `id_product` IS NOT NULL
		GROUP BY id_product
		ORDER BY date_add ASC';
		$result = Db::getInstance()->ExecuteS($sql);

		if (empty($result))
			return array();
		$products = array();
		foreach ($result AS $k => $row) {
                        $product = array('id_product' => $row['id_product']);
                        $row = new Product($row['id_product']);
                        foreach ($row as $key => $value)
                                $product[$key] = $value;
			$products[] = $product;
                }
		$this->_products = $products;
		return $this->_products;
	}

	public function containsProduct($id_product)
	{
		return Db::getInstance()->getRow('
			SELECT id_product
			FROM `'._DB_PREFIX_.'compare_cart_product`
			WHERE `id_product` = '.intval($id_product));
	}


	/**
	 * Add product
	 *
	 * @param integer $id_product Product ID
	 */
	public	function addProduct($id_product)
	{
		/* Check if the product is already in the cart */
		$result = $this->containsProduct($id_product);

		/* Add product */
		if (   !Db::getInstance()->NumRows()
		    && !Db::getInstance()->AutoExecute(_DB_PREFIX_.'compare_cart_product',
							    array('id_product' => intval($id_product),
								  'id_compare_cart' => intval($this->id),
								  'date_add' => pSql(date('Y-m-d H:i:s'))),
							    'INSERT'))
				return false;
	}

	/**
	 * Delete a product from the cart
	 *
	 * @param integer $id_product Product ID
	 * @return boolean result
	 */
	public	function deleteProduct($id_product)
	{
		return Db::getInstance()->Execute('DELETE FROM `'._DB_PREFIX_.'compare_cart_product` WHERE `id_product` = '.intval($id_product) . ' AND `id_compare_cart` = '.intval($this->id));
	}

	/**
	* Return useful informations for cart
	*
	* @return array Cart details
	*/
	function getSummaryDetails()
	{
		global $cookie;
		return array(
			'products' => $this->getProducts(false),
			);
	}
	
	static public function getCustomerCarts($id_customer)
    {
	 	$result = Db::getInstance()->ExecuteS('
		 	SELECT *
			FROM '._DB_PREFIX_.'cart c
			WHERE c.`id_customer` = '.intval($id_customer).'
			ORDER BY c.`date_add` DESC');
	 	return $result;
    }
}
