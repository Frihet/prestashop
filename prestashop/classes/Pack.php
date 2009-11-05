<?php

class Pack extends Product
{
	private static $cachePack = array();
	private static $cachePackItems = array();
	
	public static function isPack($id_product)
	{
		$result = Db::getInstance()->getRow('SELECT COUNT(*) as items FROM '._DB_PREFIX_.'pack where id_product_pack = '.intval($id_product));
		return $result['items'] > 0 ? true : false;
	}
	
	public static function isPacked($id_product)
	{
		$result = Db::getInstance()->getRow('SELECT COUNT(*) as packs FROM '._DB_PREFIX_.'pack where id_product_item = '.intval($id_product));
		return $result['packs'] > 0 ? true : false;
	}
	
	public static function noPackPrice($id_product)
	{
		$sum = 0;
		$items = self::getItems($id_product, Configuration::get('PS_LANG_DEFAULT'));
		foreach ($items as $item)
			$sum += $item->getPriceLC() * $item->pack_quantity;
		return $sum;		
	}
	
	public static function getItems($id_product, $id_lang)
	{
		if (in_array($id_product, self::$cachePackItems))
			return self::$cachePackItems[$id_product];
		$result = Db::getInstance()->ExecuteS('SELECT id_product_item, quantity FROM '._DB_PREFIX_.'pack where id_product_pack = '.intval($id_product));
		$arrayResult = array();
		foreach ($result AS $row)
		{
			$p = new Product($row['id_product_item'], false, intval($id_lang));
			$p->pack_quantity = $row['quantity'];
			$arrayResult[] = $p;
		}
		self::$cachePackItems[$id_product] = $arrayResult;
		return self::$cachePackItems[$id_product];
	}
	
	public static function getItemTable($id_product, $id_lang, $full = false)
	{
		global $currency;

		$product_groups_where = 'OR ' . Tools::slqIn("pp.id_group", Tools::colArray(Group::getGroupsForCustomer(), 'id_group'));

		$default_currency = Configuration::get('PS_CURRENCY_DEFAULT');

		$sql = "
		 SELECT
                  p.*,
		  pl.*,
		  i.`id_image`,
		  il.`legend`,
		  t.`rate`,
		  cl.`name` AS category_default,
		  a.quantity AS pack_quantity
		 FROM
                  `PREFIX_pack` a
		  LEFT JOIN `PREFIX_product` p ON
                   p.id_product = a.id_product_item
		  LEFT JOIN `PREFIX_product_lang` pl ON
                   p.id_product = pl.id_product AND pl.`id_lang` = {$id_lang}
		  LEFT JOIN `PREFIX_image` i ON
                   i.`id_product` = p.`id_product` AND i.`cover` = 1
		  LEFT JOIN `PREFIX_image_lang` il ON
                   i.`id_image` = il.`id_image` AND il.`id_lang` = {$id_lang}
		  LEFT JOIN `PREFIX_category_lang` cl ON
                   p.`id_category_default` = cl.`id_category` AND cl.`id_lang` = {$id_lang}
		  LEFT JOIN
		   (SELECT pp.id_product, min(abs(pp.id_currency - {$currency->id})) as currency_diff
		    FROM PREFIX_product_price pp
		    WHERE (pp.id_currency in ({$currency->id}, {$default_currency}) AND pp.id_group IS NULL {$product_groups_where})
                    GROUP BY pp.id_product) AS pp1 ON
                   pp1.id_product = p.id_product
		  LEFT JOIN
		   (SELECT pp.id_product, pp.id_currency, min(pp.price) as min_price
		    FROM PREFIX_product_price pp
		    WHERE (pp.id_group IS NULL {$product_groups_where})
                    GROUP BY pp.id_product, pp.id_currency) AS pp2 ON
                   pp2.id_product = p.id_product
                   AND abs(pp2.id_currency - {$currency->id}) = pp1.currency_diff
                  LEFT JOIN `PREFIX_product_price` pp3 ON
                   pp3.id_product = p.id_product
                   AND abs(pp3.id_currency - {$currency->id}) = pp1.currency_diff
                   AND pp3.price = pp2.min_price
		  LEFT JOIN `PREFIX_tax` t ON
                   t.`id_tax` = pp3.`id_tax`
		 WHERE a.`id_product_pack` = {$id_product}";

		$sql = str_replace('PREFIX_', _DB_PREFIX_, $sql);
		$result = Db::getInstance()->ExecuteS($sql);
		if (!$full)
			return $result;
			
		$arrayResult = array();
		foreach ($result as $row)
			if (!Pack::isPack($row['id_product']))
				$arrayResult[] = Product::getProductProperties($id_lang, $row);
		return $arrayResult;
	}
	
	public static function getPacksTable($id_product, $id_lang, $full = false, $limit = NULL)
	{

		global $currency;

		$product_groups_where = 'OR ' . Tools::slqIn("pp.id_group", Tools::colArray(Group::getGroupsForCustomer(), 'id_group'));
		$default_currency = Configuration::get('PS_CURRENCY_DEFAULT');

		$sql = "
		 SELECT
                  p.*, pl.*, i.`id_image`, il.`legend`, t.`rate`
		 FROM
                  `PREFIX_product` p
		  NATURAL LEFT JOIN `PREFIX_product_lang` pl
		  LEFT JOIN `PREFIX_image` i ON
                   i.`id_product` = p.`id_product` AND i.`cover` = 1
		  LEFT JOIN `PREFIX_image_lang` il ON
                   i.`id_image` = il.`id_image` AND il.`id_lang` = {$id_lang}
		  LEFT JOIN
		   (SELECT pp.id_product, min(abs(pp.id_currency - {$currency->id})) as currency_diff
		    FROM PREFIX_product_price pp
		    WHERE (pp.id_currency in ({$currency->id}, {$default_currency}) AND pp.id_group IS NULL {$product_groups_where})
                    GROUP BY pp.id_product) AS pp1 ON
                   pp1.id_product = p.id_product
		  LEFT JOIN
		   (SELECT pp.id_product, pp.id_currency, min(pp.price) as min_price
		    FROM PREFIX_product_price pp
		    WHERE (pp.id_group IS NULL {$product_groups_where})
                    GROUP BY pp.id_product, pp.id_currency) AS pp2 ON
                   pp2.id_product = p.id_product
                   AND abs(pp2.id_currency - {$currency->id}) = pp1.currency_diff
                  LEFT JOIN `PREFIX_product_price` pp3 ON
                   pp3.id_product = p.id_product
                   AND abs(pp3.id_currency - {$currency->id}) = pp1.currency_diff
                   AND pp3.price = pp2.min_price
		  LEFT JOIN `PREFIX_tax` t ON
                   t.`id_tax` = pp3.`id_tax`
		 WHERE pl.`id_lang` = {$id_lang}
		 AND p.`id_product` IN (
			 SELECT a.`id_product_pack`
			 FROM `PREFIX_pack` a
			 WHERE a.`id_product_item` = {$id_product})
		";
		$sql = str_replace('PREFIX_', _DB_PREFIX_, $sql);
		$result = Db::getInstance()->ExecuteS($sql);

		if ($limit)
			$sql .= ' LIMIT '.intval($limit);
		$result = Db::getInstance()->ExecuteS($sql);
		if (!$full)
			return $result;
			
		$arrayResult = array();
		foreach ($result as $row)
			if (!Pack::isPacked($row['id_product']))
				$arrayResult[] = Product::getProductProperties($id_lang, $row);
		return $arrayResult;
	}
	
	public static function deleteItems($id_product)
	{
		return Db::getInstance()->Execute('DELETE FROM `'._DB_PREFIX_.'pack` WHERE `id_product_pack` = '.intval($id_product));
	}
	
	public static function addItems($id_product, $ids)
	{
		array_pop($ids);
		foreach ($ids as $id_product_item)
		{
			$idQty = explode('x', $id_product_item);
			if (!Db::getInstance()->AutoExecute(_DB_PREFIX_.'pack', array('id_product_pack' => intval($id_product), 'id_product_item' => intval($idQty[1]), 'quantity' => intval($idQty[0])), 'INSERT'))
				return false;
		}
		return true;
	}
	
	public static function duplicate($id_product_old, $id_product_new)
	{
		Db::getInstance()->Execute('INSERT INTO '._DB_PREFIX_.'pack (id_product_pack, id_product_item, quantity)
		(SELECT '.intval($id_product_new).', id_product_item, quantity FROM '._DB_PREFIX_.'pack WHERE id_product_pack = '.intval($id_product_old).')');
		
		// If return query result, a non-pack product will return false
		return true;
	}
}

?>
