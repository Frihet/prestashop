<?php

/**
  * OrderVendor class, OrderVendor.php
  * @category classes
  *
  * @author Egil Möller <egil.moller@freecode.no>
  * @copyright FreeCode AS
  * @license http://www.opensource.org/licenses/osl-3.0.php Open-source licence 3.0
  * @license http://www.fsf.org/licensing/licenses/gpl.html GPL 3.0+
  * @version 0.1
  *
  */

class OrderProductVendor
{
	public function getProductsWithVendors($cart) {
		$deliveryAddress = new Address(intval($cart->id_address_delivery));

		$product_lines = $cart->getProducts();
		foreach ($product_lines as $index => $product_line) {
			$query = 'select id_vendor from PREFIX_cart_product where id_cart = "' . pSql($cart->id) . '" and id_product = "' . pSql($product_line['id_product']) . '"';
			$query = str_replace('PREFIX_', _DB_PREFIX_, $query);
			$row = Db::getInstance()->getRow($query);
			$id_vendor = 0;
			if ($row)
				$id_vendor = $row['id_vendor'];
			$product_lines[$index]['vendors'] = self::getVendorsForPostcode($deliveryAddress->id_country, $deliveryAddress->postcode, array($product_line['id_product']), $id_vendor);
                }
		return $product_lines;
	}

	public function getVendorsForPostcode($id_country, $postcode, $id_products, $id_vendor_default) {
		// Okay, here we go: Select all vendors
		// supporting the product that services an
		// area covering the delivery postcode. This
		// is basically a Pythagoras

                // Espen Lyngaas added line to remove spaces in postcodes
                $postcode = str_replace(" ","",$postcode);

		$products_sql = '"' . $id_products[0] . '"';
		foreach ($id_products as $id_product)
			$product_sql .= ', "' . $id_product . '"';
		$query = '
			select
			 vendor_support.id_product,
			 vendor.id_vendor,
			 vendor.title,
			 postcode_vendor.name as postcode,
			 sqrt(  power(abs(postcode_vendor.latitude - postcode_customer.latitude),2)
			      + power(abs(postcode_vendor.longitude - postcode_customer.longitude),2)) as distance
			from
			 PREFIX_vendor_support,
			 PREFIX_vendor,
			 PREFIX_postcode as postcode_vendor,
			 PREFIX_postcode as postcode_customer
			where
			 vendor_support.id_product in (' . $products_sql . ')
			 and postcode_customer.name = "' . pSql($postcode) . '"
			 and postcode_customer.id_country = "' . pSql($id_country) . '"

			 and vendor_support.id_vendor = vendor.id_vendor
			 and vendor.id_postcode = postcode_vendor.id_postcode

			 and postcode_vendor.id_country = "' . pSql($id_country) . '"
			 and sqrt(  power(abs(postcode_vendor.latitude - postcode_customer.latitude),2)
				  + power(abs(postcode_vendor.longitude - postcode_customer.longitude),2)) <= vendor.distance
                        order by
                         abs(vendor.id_vendor - "' . pSql($id_vendor_default) . '")
			 ';
		$query = str_replace('PREFIX_', _DB_PREFIX_, $query);
		$result = Db::getInstance()->ExecuteS($query);
		$vendors = array();
		if ($result)
			foreach ($result as $supporting_vendor)
				$vendors[] = $supporting_vendor;
		return $vendors;
	}

	public function setVendor($cart, $id_product, $id_vendor) {
		$query = 'update PREFIX_cart_product set id_vendor = "' . $id_vendor . '" where id_cart = "' . $cart->id . '" and id_product = "' . $id_product . '"';
		$query = str_replace('PREFIX_', _DB_PREFIX_, $query);
		Db::getInstance()->Execute($query);
        }
}
