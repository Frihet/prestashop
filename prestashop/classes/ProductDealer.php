<?php
class ProductDealer extends Product {
	
	
	public	function __construct($id_product = NULL, $id_lang = NULL) {
		parent::__construct($id_product, false, $id_lang);
		
	}
	
	static public function getDealerCategories($id_lang, $id_category) {
		
	}
	
	static public function getDealers($id_lang, $id_category = false) {
		/*
		$category_join = '';
		$category_where = '';
		if ($id_category) {
			$category_join = "
			  LEFT JOIN `PREFIX_category_product` c ON
			    c.`id_product` = p.`id_product`
			";
			$category_where = " AND c.`id_category` = {$id_category}";
		}
		$sql = "
		SELECT
			p.*
		 FROM
		  `PREFIX_product` p
		  LEFT JOIN `PREFIX_product_lang` pl ON p.`id_product` = pl.`id_product`
		  {$category_join}
		WHERE
		  p.type = 'dealer' AND
		  pl.`id_lang` = {$id_lang}
		  {$category_where}
		";
		*/
		$id_category = intval($id_category);
		
		$sql = '
		SELECT
		  CONCAT(
		    c.`id_category`, ",",
		    c2.`id_category`, ",",
		    c3.`id_category`, ",",
		    c4.`id_category`, ",",
		    c5.`id_category`
		  ) AS `id_categorytree`,
		  p.*
		 FROM
		  `product` p
		  LEFT JOIN `product_lang` pl ON p.`id_product` = pl.`id_product`
		  LEFT JOIN `category_product` catp ON p.`id_product` = catp.`id_product`
		  LEFT JOIN `category` c ON c.`id_category` = catp.`id_category`
		  LEFT JOIN `category` c2 ON c.`id_parent` = c2.`id_category`
		  LEFT JOIN `category` c3 ON c2.`id_parent` = c3.`id_category`
		  LEFT JOIN `category` c4 ON c3.`id_parent` = c4.`id_category`
		  LEFT JOIN `category` c5 ON c4.`id_parent` = c5.`id_category`
		WHERE
		  p.type = "dealer" AND
		  pl.`id_lang` = '.intval($id_lang).' AND (
		     c.`id_category` = '.$id_category.' OR
		     c2.`id_category` = '.$id_category.' OR
		     c3.`id_category` = '.$id_category.' OR
		     c4.`id_category` = '.$id_category.' OR
		     c5.`id_category` = '.$id_category.'
		  )
		';
		$sql = str_replace('PREFIX_', _DB_PREFIX_, $sql);
		$rq = Db::getInstance()->ExecuteS($sql);
		
		return ($rq);
	}
	
	public function findVendordata($id_product = false) {
		if ($id_product === false) {
			$id_product = $this->id;
		}
		
		$sql = "
		SELECT
			v.title,
			v.hansa_classification,
			v.distance,
			c.email,
			c.classifications,
			c.hours,
			c.fax,
			c.blog_url,
			v.latlng,
			addr.address1,
			addr.address2,
			addr.postcode,
			addr.city,
			addr.phone,
			addr.phone_mobile
		 FROM
		  `PREFIX_product` p
		  LEFT JOIN `PREFIX_vendor` v ON p.`id_vendor` = v.`id_vendor`
		  LEFT JOIN `PREFIX_customer` c ON v.`id_customer` = c.`id_customer`
		  LEFT JOIN `PREFIX_address` addr ON c.`id_customer` = addr.`id_customer` 
		WHERE
		  p.`id_product` = " . $id_product . "
		GROUP BY
		  p.`id_product`
		";
		$sql = str_replace('PREFIX_', _DB_PREFIX_, $sql);
		$rq = Db::getInstance()->ExecuteS($sql);
		
		return ($rq);
	}
	
	/**
	 * Finds correct category id based on a top level category id.
	 */
	public function findCategoryId($id_top_category) {
		$sql = "
		SELECT
			c.id_category
		FROM
			`PREFIX_product` p
			LEFT JOIN `PREFIX_category_product` c ON c.`id_product` = p.`id_product`
		WHERE
			p.id_product = '" . $this->id . "';
		";
		$sql = str_replace('PREFIX_', _DB_PREFIX_, $sql);
		$rows = Db::getInstance()->ExecuteS($sql);
		foreach($rows as $row) {
			$id_parent = $row['id_category'];
			
			/* CouldDO: Move this next bit to the Category class */
			while(true) {
				$sql = str_replace('PREFIX_', _DB_PREFIX_,
									"SELECT `id_parent` FROM `PREFIX_category` WHERE `id_category` = {$id_parent}");
				$id_parent = (int) Db::getInstance()->getValue($sql);
				if ($id_parent == $id_top_category) {
					return (int)$row['id_category'];
				}
				if ($id_parent <= 1 || !$id_parent)
					break;
			}
		}
		return -1;
	}
	
}