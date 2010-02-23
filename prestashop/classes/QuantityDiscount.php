<?php

/**
  * Quantity discount class, QuantityDiscount.php
  * Discount breaks management
  * @category classes
  *
  * @author PrestaShop <support@prestashop.com>
  * @copyright PrestaShop
  * @license http://www.opensource.org/licenses/osl-3.0.php Open-source licence 3.0
  * @version 1.2
  *
  */

class QuantityDiscount extends ObjectModel
{
 	/** @var integer Discount type id */
	public 	$id_discount_type;

 	/** @var integer Product price id */
	public 	$id_product_price;

	/** @var integer Product attribute price combinaison id */
	public	$id_product_attribute_price;

	/** @var integer Quantity range */
	public	$quantity;

	/** @var float Rate (%) or reduction */
	public	$value;

 	protected 	$fieldsRequired = array('id_discount_type', 'id_product_price', 'quantity', 'value');
 	protected 	$fieldsValidate = array('id_discount_type' => 'isUnsignedId', 'id_product_price' => 'isUnsignedId',
	 'id_product_attribute_price' => 'isUnsignedId', 'quantity' => 'IsUnsignedInt', 'value' => 'isFloat');

	protected 	$table = 'discount_quantity';
	protected 	$identifier = 'id_discount_quantity';

	public function getFields()
	{
		parent::validateFields();
		$fields['id_discount_type'] = intval($this->id_discount_type);
		$fields['id_product_price'] = intval($this->id_product_price);
		$fields['id_product_attribute_price'] = intval($this->id_product_attribute_price);
		$fields['quantity'] = intval($this->quantity);
		$fields['value'] = floatval($this->value);
		return $fields;
	}

    public static function getValue($product_price, $id_discount_type, $value)
    {
		if ($id_discount_type == 1)
		{
			$percentage = $value / 100;
			return $percentage * floatval($product_price);
		}
		elseif ($id_discount_type == 2)
			return $value;
		return 0;
    }

    public static function getQuantityDiscounts($id_product, $price)
	{
		global $cookie;

		/* Well, we don't get $id_product_attribute from
		   anywhere so I guess we can't do much, but the DB
		   schema accommodates for price reductions per
		   attribute... */
		$base_price = Product::getBasePriceStaticLC($id_product, NULL);

		$result = Db::getInstance()->ExecuteS('
		SELECT *
		FROM '._DB_PREFIX_.'discount_quantity dq
		LEFT JOIN `'._DB_PREFIX_.'discount_type_lang` dtl ON (dq.`id_discount_type` = dtl.`id_discount_type` AND dtl.`id_lang` = '.intval($cookie->id_lang).')
		WHERE dq.`id_product_price` = '.intval($base_price['id_product_price']).' ORDER BY dq.`quantity` ASC');
		foreach ($result AS $key => &$row)
		{
			$row['real_value'] = QuantityDiscount::getValue(floatval($price), intval($row['id_discount_type']), $row['value']);
			$row['nextQuantity'] = (isset($result[$key + 1]) ? intval($result[$key + 1]['quantity']) : -1);
		}
        return $result;
	}

    public static function getDiscountFromQuantity($id_product, $quantity)
    {

	/* Well, we don't get $id_product_attribute from
	   anywhere so I guess we can't do much, but the DB
	   schema accommodates for price reductions per
	   attribute... */
	$base_price = Product::getBasePriceStaticLC($id_product, NULL);

        $result = Db::getInstance()->getRow('
		SELECT dq.`id_discount_quantity`
		FROM '._DB_PREFIX_.'discount_quantity dq
		WHERE dq.`quantity` <= '.intval($quantity).'
		AND dq.`id_product_price` = '.intval($base_price['id_product_price']).'
        ORDER BY quantity DESC');
        if (!$result)
            return false;
        return new QuantityDiscount($result['id_discount_quantity']);
    }
}
?>
