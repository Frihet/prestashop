<?php

/**
  * Event search class, EventSearch.php
  * Search management
  * @category classes
  *
  * @author PrestaShop <support@prestashop.com>
  * @copyright PrestaShop
  * @copyright FreeCode AS, Egil Möller <egil.moller@freecode.no>
  * @license http://www.opensource.org/licenses/osl-3.0.php Open-source licence 3.0
  * @version 0.1
  *
  */

include_once(dirname(__file__)."/../../classes/Search.php");
require_once(dirname(__file__)."/functions.php");

class EventSearch extends Search
{
	public static function find_event($id_lang, $event_date, $pageNumber = 1, $pageSize = 1)
	{
		global $cookie;

		$id_lang = pSQL($id_lang);

		// TODO : smart page management
		if ($pageNumber < 1) $pageNumber = 1;
		if ($pageSize < 1) $pageSize = 1;
		
		$customer_select = "";
		$customer_join = "";
		$customer_where = "";
		if ($cookie->id_customer) {
		     	$customer_select = ", cg.`id_group`";
		        $customer_join = "INNER JOIN `PREFIX_customer_group` cg ON (cg.`id_group` = ctg.`id_group`)";
		        $customer_where = "cg.`id_customer` = {$cookie->id_customer} OR ";
		}

		$pageStart = ($pageNumber - 1) * $pageSize;

		$price_sql = Product::getProductPriceSql('p.id_product', 'pp');

		$event_date_end = normalize_date("{$event_date} + 1 day");

		// FIXME: Handle new products properly (see classes/Product.php:isNew() for corect join clause)
		$sql = "
		 SELECT
                  SQL_CALC_FOUND_ROWS p.*, pp.*, 0 as `new`, pl.`description_short`, pl.`available_now`, pl.`available_later`, pl.`link_rewrite`, pl.`name`,
		  t.`rate`, i.`id_image`, il.`legend`, m.`name` AS manufacturer_name {$customer_select}
		 FROM
		  PREFIX_customization_field as f
		  JOIN PREFIX_customization_field_schedule as s on
		   not (   s.end_time < '{$event_date}'
		        or s.start_time > '{$event_date_end}')
		   and s.id_customization_field = f.id_customization_field
	 	  JOIN PREFIX_product p on
		   p.id_product = f.id_product
		  LEFT JOIN `PREFIX_product_lang` pl ON (p.`id_product` = pl.`id_product` AND pl.`id_lang` = {$id_lang})
		  LEFT OUTER JOIN `PREFIX_image` i ON (i.`id_product` = p.`id_product` AND i.`cover` = 1)
		  LEFT JOIN `PREFIX_image_lang` il ON (i.`id_image` = il.`id_image` AND il.`id_lang` = {$id_lang})

		  {$price_sql}
		 
		  LEFT JOIN `PREFIX_tax` t ON (pp.`id_tax` = t.`id_tax`)

		  LEFT JOIN `PREFIX_manufacturer` m ON (m.`id_manufacturer` = p.`id_manufacturer`)
		  LEFT JOIN `PREFIX_category_product` cp ON (cp.`id_product` = p.`id_product`)
		  INNER JOIN `PREFIX_category_group` ctg ON (ctg.`id_category` = cp.`id_category`)
		  {$customer_join}

		 WHERE
		  p.active = 1
		  AND ({$customer_where} ctg.`id_group` = 1)

		 GROUP BY p.`id_product`

		 ORDER BY start_time ASC

		 LIMIT {$pageStart}, {$pageSize}";

		$sql = str_replace('PREFIX_', _DB_PREFIX_, $sql);
		$result = Db::getInstance()->ExecuteS($sql);
		$total = Db::getInstance()->getValue('SELECT FOUND_ROWS()');

		return array('total' => $total,'result' => Product::getProductsProperties($id_lang, $result));
	}
	
}
	
?>
