<?php

/**
  * Advanced search class, AdvSearch.php
  * Search management
  * @category classes
  *
  * @author PrestaShop <support@prestashop.com>
  * @copyright PrestaShop
  * @license http://www.opensource.org/licenses/osl-3.0.php Open-source licence 3.0
  * @version 1.2
  *
  */

include_once(dirname(__file__)."/../../../classes/Search.php");

class AdvSearch
{
	public static function sanitize($string, $id_lang, $indexation = false)
	{
		$string = Tools::strtolower(strip_tags($string));
		$string = html_entity_decode($string, ENT_NOQUOTES, 'utf-8');

		$string = preg_replace('/(['.PREG_CLASS_NUMBERS.']+)['.PREG_CLASS_PUNCTUATION.']+(?=['.PREG_CLASS_NUMBERS.'])/u', '\1', $string);
		if ($indexation)
		{
			$string = preg_replace('/[._-]+/', '', $string);
			$string = preg_replace('/['.PREG_CLASS_SEARCH_EXCLUDE.'-\x{2f}]+/u', ' ', $string);
		}
		else
		{
			$string = preg_replace('/[._]+/', '', $string);
			$string = ltrim(preg_replace('/([^ ])-/', '$1', ' '.$string));
			$string = preg_replace('/[._]+/', '', $string);
			$string = preg_replace('/[^\s]-+/', '', $string);
			$string = preg_replace('/['.PREG_CLASS_SEARCH_EXCLUDE.']+/u', ' ', $string);
		}
		$blacklist = Configuration::get('PS_SEARCH_BLACKLIST', $id_lang);
		if (!empty($blacklist))
		{
			$string = preg_replace('/(?<=\s)('.$blacklist.')(?=\s)/Su', '', $string);
			$string = preg_replace('/^('.$blacklist.')(?=\s)/Su', '', $string);
			$string = preg_replace('/(?<=\s)('.$blacklist.')$/Su', '', $string);
			$string = preg_replace('/^('.$blacklist.')$/Su', '', $string);
		}
		
		if (!$indexation)
		{
			$alias = new Alias(NULL, $string);
			if (Validate::isLoadedObject($alias))
				$string = $alias->search;
		}
		
		if ($indexation)
		{
			$minWordLen = Configuration::get('PS_SEARCH_MINWORDLEN');
			if ($minWordLen)
			{
				$string = preg_replace('/(?<=\s)[^\s]{1,'.$minWordLen.'}(?=\s)/Su', ' ', $string);
				$string = preg_replace('/^[^\s]{1,'.$minWordLen.'}(?=\s)/Su', '', $string);
				$string = preg_replace('/(?<=\s)[^\s]{1,'.$minWordLen.'}$/Su', '', $string);
				$string = preg_replace('/^[^\s]{1,'.$minWordLen.'}$/Su', '', $string);
			}
		}
		
		$string = trim(preg_replace('/\s+/', ' ', $string));
		return $string;
	}

	public static function find($id_lang, $expr, $pageNumber = 1, $pageSize = 1, $orderBy = 'position', $orderWay = 'desc', $ajax = false)
	{
		global $cookie;

		// TODO : smart page management
		if ($pageNumber < 1) $pageNumber = 1;
		if ($pageSize < 1) $pageSize = 1;
		
		if (!Validate::isOrderBy($orderBy) OR !Validate::isOrderWay($orderWay))
			die(Tools::displayError());
			
		$whereArray = array();
		$scoreArray = array();
		$words = explode(' ', AdvSearch::sanitize($expr, $id_lang));

		foreach ($words as $key => $word)
			if (!empty($word))
			{
				$word = str_replace('%', '\\%', $word);
				$word = str_replace('_', '\\_', $word);
				$whereArray[] = ' p.id_product '.($word[0] == '-' ? 'NOT' : '').' IN (
					SELECT id_product
					FROM '._DB_PREFIX_.'search_word sw
					LEFT JOIN '._DB_PREFIX_.'search_index si ON sw.id_word = si.id_word
					WHERE sw.id_lang = '.intval($id_lang).'
					AND sw.word LIKE '.($word[0] == '-' ? ' \''.pSQL(substr($word, 1, PS_SEARCH_MAX_WORD_LENGTH)).'%\'' : '\''.pSQL(substr($word, 0, PS_SEARCH_MAX_WORD_LENGTH)).'%\'').'
				) ';
				if ($word[0] != '-')
					$scoreArray[] = 'sw.word LIKE \''.pSQL(substr($word, 0, PS_SEARCH_MAX_WORD_LENGTH)).'%\'';
			}
			else
				unset($words[$key]);
		if (!sizeof($words))
			return ($ajax ? array() : array('total' => 0, 'result' => array()));
			
		$score = '';
		if (sizeof($scoreArray))
			$score = ',(
				SELECT SUM(weight)
				FROM '._DB_PREFIX_.'search_word sw
				LEFT JOIN '._DB_PREFIX_.'search_index si ON sw.id_word = si.id_word
				WHERE sw.id_lang = '.intval($id_lang).'
				AND si.id_product = p.id_product
				AND ('.implode(' OR ', $scoreArray).')
			) as position';
		
		if ($ajax)
		{
			$queryResults = '
			SELECT p.id_product, pl.name as pname, IF(cl.name REGEXP "^[0-9]{2}\\.", SUBSTRING(cl.name, 4), cl.name) as cname	'.$score.', cl.link_rewrite as crewrite, pl.link_rewrite as prewrite
			FROM '._DB_PREFIX_.'product p
			LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (p.`id_product` = pl.`id_product` AND pl.`id_lang` = '.intval($id_lang).')
			LEFT JOIN `'._DB_PREFIX_.'category_lang` cl ON (p.`id_category_default` = cl.`id_category` AND cl.`id_lang` = '.intval($id_lang).')
			LEFT JOIN `'._DB_PREFIX_.'category_product` cp ON (cp.`id_product` = p.`id_product`)
			INNER JOIN `'._DB_PREFIX_.'category_group` ctg ON (ctg.`id_category` = cp.`id_category`)
			'.($cookie->id_customer ? 'INNER JOIN `'._DB_PREFIX_.'customer_group` cg ON (cg.`id_group` = ctg.`id_group`)' : '').'
			WHERE '.implode(' AND ', $whereArray).'
			AND p.active = 1
			AND ('.($cookie->id_customer ? 'cg.`id_customer` = '.intval($cookie->id_customer).' OR' : '').' ctg.`id_group` = 1)
			GROUP BY p.`id_product`
			ORDER BY position DESC
			LIMIT 10';
			return Db::getInstance()->ExecuteS($queryResults);
		}
		
		$queryResults = '
		SELECT SQL_CALC_FOUND_ROWS p.*, pl.`description_short`, pl.`available_now`, pl.`available_later`, pl.`link_rewrite`, pl.`name`,
		t.`rate`, i.`id_image`, il.`legend`, m.`name` AS manufacturer_name '.($cookie->id_customer ? ', cg.`id_group`' : '').'
		'.$score.'
		FROM '._DB_PREFIX_.'product p
		LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (p.`id_product` = pl.`id_product` AND pl.`id_lang` = '.intval($id_lang).')
		LEFT OUTER JOIN `'._DB_PREFIX_.'image` i ON (i.`id_product` = p.`id_product` AND i.`cover` = 1)
		LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (i.`id_image` = il.`id_image` AND il.`id_lang` = '.intval($id_lang).')
		LEFT JOIN `'._DB_PREFIX_.'tax` t ON (p.`id_tax` = t.`id_tax`)
		LEFT JOIN `'._DB_PREFIX_.'manufacturer` m ON (m.`id_manufacturer` = p.`id_manufacturer`)
		LEFT JOIN `'._DB_PREFIX_.'category_product` cp ON (cp.`id_product` = p.`id_product`)
		INNER JOIN `'._DB_PREFIX_.'category_group` ctg ON (ctg.`id_category` = cp.`id_category`)
		'.($cookie->id_customer ? 'INNER JOIN `'._DB_PREFIX_.'customer_group` cg ON (cg.`id_group` = ctg.`id_group`)' : '').'
		WHERE '.implode(' AND ', $whereArray).'
		AND p.active = 1
		AND ('.($cookie->id_customer ? 'cg.`id_customer` = '.intval($cookie->id_customer).' OR' : '').' ctg.`id_group` = 1)
		GROUP BY p.`id_product`
		'.($orderBy ? 'ORDER BY  '.$orderBy : '').($orderWay ? ' '.$orderWay : '').'
		LIMIT '.intval(($pageNumber - 1) * $pageSize).','.intval($pageSize);
		$result = Db::getInstance()->ExecuteS($queryResults);
		$total = Db::getInstance()->getValue('SELECT FOUND_ROWS()');

		Module::hookExec('search', array('expr' => $expr, 'total' => $total));

		return array('total' => $total,'result' => Product::getProductsProperties($id_lang, $result));
	}
	
	public static function getTags($db, $id_product, $id_lang)
	{
		$tags = '';
		$tagsArray = $db->ExecuteS('
		SELECT t.name FROM '._DB_PREFIX_.'product_tag pt
		LEFT JOIN '._DB_PREFIX_.'tag t ON (pt.id_tag = t.id_tag AND t.id_lang = '.intval($id_lang).')
		WHERE pt.id_product = '.intval($id_product));
		foreach ($tagsArray as $tag)
			$tags .= $tag['name'].' ';
		return $tags;
	}

	public static function getAttributes($db, $id_product, $id_lang)
	{
		$attributes = '';
		$attributesArray = $db->ExecuteS('
		SELECT al.name FROM '._DB_PREFIX_.'product_attribute pa
		LEFT JOIN '._DB_PREFIX_.'product_attribute_combination pac ON pa.id_product_attribute = pac.id_product_attribute
		LEFT JOIN '._DB_PREFIX_.'attribute_lang al ON (pac.id_attribute = al.id_attribute AND al.id_lang = '.intval($id_lang).')
		WHERE pa.id_product = '.intval($id_product));
		foreach ($attributesArray as $attribute)
			$attributes .= $attribute['name'].' ';
		return $attributes;
	}

	public static function getFeatures($db, $id_product, $id_lang)
	{
		$features = '';
		$featuresArray = $db->ExecuteS('
		SELECT fvl.value FROM '._DB_PREFIX_.'feature_product fp
		LEFT JOIN '._DB_PREFIX_.'feature_value_lang fvl ON (fp.id_feature_value = fvl.id_feature_value AND fvl.id_lang = '.intval($id_lang).')
		WHERE fp.id_product = '.intval($id_product));
		foreach ($featuresArray as $feature)
			$features .= $feature['value'].' ';
		return $features;
	}
	
	public static function indexation($full = false)
	{
		$db = Db::getInstance();
		
		if ($full)
		{
			$db->Execute('TRUNCATE '._DB_PREFIX_.'search_index');
			$db->Execute('TRUNCATE '._DB_PREFIX_.'search_word');
			$db->Execute('UPDATE '._DB_PREFIX_.'product SET indexed = 0');
		}
		else
			$db->Execute('DELETE FROM '._DB_PREFIX_.'search_index WHERE id_product IN (SELECT id_product FROM '._DB_PREFIX_.'product WHERE indexed = 0)');
	
		$weightArray = array(
			'pname' => Configuration::get('PS_SEARCH_WEIGHT_PNAME'),
			'reference' => Configuration::get('PS_SEARCH_WEIGHT_REF'),
			'ean13' => Configuration::get('PS_SEARCH_WEIGHT_REF'),
			'description_short' => Configuration::get('PS_SEARCH_WEIGHT_SHORTDESC'),
			'description' => Configuration::get('PS_SEARCH_WEIGHT_DESC'),
			'cname' => Configuration::get('PS_SEARCH_WEIGHT_CNAME'),
			'mname' => Configuration::get('PS_SEARCH_WEIGHT_MNAME'),
			'tags' => Configuration::get('PS_SEARCH_WEIGHT_TAG'),
			'attributes' => Configuration::get('PS_SEARCH_WEIGHT_ATTRIBUTE'),
			'features' => Configuration::get('PS_SEARCH_WEIGHT_FEATURE')
		);
		
		$products = $db->ExecuteS('
		SELECT p.id_product, pl.id_lang, pl.name as pname, p.reference, p.ean13, pl.description_short, pl.description, cl.name as cname, m.name as mname
		FROM '._DB_PREFIX_.'product p
		LEFT JOIN '._DB_PREFIX_.'product_lang pl ON p.id_product = pl.id_product
		LEFT JOIN '._DB_PREFIX_.'category_lang cl ON (cl.id_category = p.id_category_default AND pl.id_lang = cl.id_lang)
		LEFT JOIN '._DB_PREFIX_.'manufacturer m ON m.id_manufacturer = p.id_manufacturer
		WHERE p.indexed = 0', false);
		
		while ($product = $db->nextRow($products))
		{
			$product['tags'] = self::getTags($db, $product['id_product'], $product['id_lang']);
			$product['attributes'] = self::getAttributes($db, $product['id_product'], $product['id_lang']);
			$product['features'] = self::getFeatures($db, $product['id_product'], $product['id_lang']);

			$pArray = array();
			foreach ($product as $key => $value)
				if (strncmp($key, 'id_', 3))
				{
					if ($key == 'cname')
						$value = Category::hideCategoryPosition($value);
					$words = explode(' ', self::sanitize($value, $product['id_lang'], true));
					foreach ($words as $word)
						if (!empty($word))
						{
							$word = substr($word, 0, PS_SEARCH_MAX_WORD_LENGTH);
							if (!isset($pArray[$word]))
								$pArray[$word] = $weightArray[$key];
							else
								$pArray[$word] += $weightArray[$key];
						}
				}
			$queryArray = array();
			$queryArray2 = array();
			foreach ($pArray as $word => $weight)
			{
				$queryArray[] = '('.intval($product['id_lang']).',\''.pSQL($word).'\')';
				$queryArray2[] = '('.intval($product['id_product']).',(SELECT id_word FROM '._DB_PREFIX_.'search_word WHERE word = \''.pSQL($word).'\' AND id_lang = '.intval($product['id_lang']).' LIMIT 1),'.intval($weight).')';
			}
			if (sizeof($queryArray) AND sizeof($queryArray2))
			{
				if (!($rows = $db->Execute('INSERT IGNORE INTO '._DB_PREFIX_.'search_word (id_lang, word) VALUES '.implode(',',$queryArray))) OR $rows != sizeof($queryArray))
					Tools::d(array(mysql_error(), $queryArray));
				if (!($rows = $db->Execute('INSERT INTO '._DB_PREFIX_.'search_index (id_product, id_word, weight) VALUES '.implode(',',$queryArray2).' ON DUPLICATE KEY UPDATE weight = weight + VALUES(weight)')) OR $rows != sizeof($queryArray2))
					Tools::d(array(mysql_error(), $queryArray2));
			}
			$db->Execute('UPDATE '._DB_PREFIX_.'product SET indexed = 1 WHERE id_product = '.intval($product['id_product']));
		}
		$db->Execute('DELETE FROM '._DB_PREFIX_.'search_word WHERE id_word NOT IN (SELECT id_word FROM '._DB_PREFIX_.'search_index)');
		return true;
	}
	/*-------------------------------advsearch---------------------------*/
	public static function find_adv($id_lang, $expr, $pageNumber = 1, $pageSize = 10, $orderBy = false, $orderWay = false)
	{
		
		if (!is_numeric($pageNumber) OR !is_numeric($pageSize) 
		OR !Validate::isValidSearch($expr)
		OR $orderBy AND !$orderWay)
			die(Tools::displayError());
		
		
		global $link;
		global $cookie;
		if($orderBy=='position')
		$orderBy='name';
		
		if (!is_numeric($pageNumber) OR !is_numeric($pageSize) 
		
		OR $orderBy AND !$orderWay)
			die(Tools::displayError());
		
		
		$alias = new Alias(NULL, $expr);
		if (Validate::isLoadedObject($alias))
			$expr = $alias->search;
		if (!Validate::isValidSearch($expr))
			die(Tools::displayError());
		if ($pageNumber < 1) $pageNumber = 1;
		if ($pageSize < 1) $pageSize = 10;

		$expr = str_replace(' ', ' ', pSQL(str_replace('\'', ' ', $expr))).'';
		
		$words = explode(' ', AdvSearch::sanitize($expr, $id_lang));
			
		/* else we search for the expression */
		
		//$search_query=(Tools::getValue('search_queryy')!=null)?', '.$this->_getScore($expr).'':'';
		///$search_query2=(Tools::getValue('search_queryy')!=null)?'AND '.$this->_getFilter($expr):'';
		
		///////////////////////////////
		foreach ($words as $key => $word)
		
			if (!empty($word))
			{
				$word = str_replace('%', '\\%', $word);
				$word = str_replace('_', '\\_', $word);
				$whereArray[] = ' p.id_product '.($word[0] == '-' ? 'NOT' : '').' IN (
					SELECT id_product
					FROM '._DB_PREFIX_.'search_word sw
					LEFT JOIN '._DB_PREFIX_.'search_index si ON sw.id_word = si.id_word
					WHERE sw.id_lang = '.intval($id_lang).'
					AND sw.word LIKE '.($word[0] == '-' ? ' \''.pSQL(substr($word, 1, PS_SEARCH_MAX_WORD_LENGTH)).'%\'' : '\''.pSQL(substr($word, 0, PS_SEARCH_MAX_WORD_LENGTH)).'%\'').'
				) ';
				if ($word[0] != '-')
					$scoreArray[] = 'sw.word LIKE \''.pSQL(substr($word, 0, PS_SEARCH_MAX_WORD_LENGTH)).'%\'';
			}
			else
				unset($words[$key]);
		if (!sizeof($words))
		$searchbywords='';
			else
		$searchbywords='AND '.implode(' AND ', $whereArray); 
		///////////////////////////////
		
		
		
		
		
		
		$displaysupp=AdvSearch::getSuppliers(Tools::getValue('supply'),'id_supplier');
		$displaymanuf=AdvSearch::getSuppliers(Tools::getValue('manufacturer'),'id_manufacturer');

		$attributes='';
		$attributes_val='';
		if($attribu=Tools::getValue('atrybut_opcja')){
		 
		if(!is_array($attribu)){
		$attrib=urldecode($attribu);
		$attrib=urldecode($attrib);
		$pos = strpos($attrib, ',');
				if ($pos === false) {
					$att=array($attrib);
				}else{
					$att=explode(",",$attrib);
				}
				$attributes=AdvSearch::attributesQuery($id_lang,$att,true);
				$attributes_val=AdvSearch::attributesQuery($id_lang,$att,false);
			
		}else{	
			if($attribu[0]>0){
				$attributes=AdvSearch::attributesQuery($id_lang,$attribu,true);
				$attributes_val=AdvSearch::attributesQuery($id_lang,$attribu,false);
			}
		}
		}
		$displaycategories=AdvSearch::queryCategories(Tools::getValue('categories'));
		$price_brutto="CASE WHEN t.rate>0 THEN
	CASE WHEN p.reduction_from!=p.reduction_to THEN
		CASE WHEN p.reduction_to>='".date("Y-m-d")."' and p.reduction_from<='".date("Y-m-d")."' THEN
			CASE WHEN p.reduction_price>0 THEN
				ROUND(((p.price*(1+(t.rate/100)))-p.reduction_price)*cu.conversion_rate,2)
			WHEN p.reduction_percent>0 THEN
				ROUND(((p.price*(1+(t.rate/100)))-((p.price*(1+(t.rate/100)))*(p.reduction_percent/100)))*cu.conversion_rate,2)
			ELSE
				ROUND((p.price*(1+t.rate/100))*cu.conversion_rate,2)
			END 
		ELSE
			ROUND((p.price*(1+t.rate/100))*cu.conversion_rate,2)
		END  
	ELSE
		CASE WHEN p.reduction_price>0 THEN
			ROUND(((p.price*(1+t.rate/100))-p.reduction_price)*cu.conversion_rate,2)
		WHEN p.reduction_percent>0 THEN
			ROUND(((p.price*(1+t.rate/100))-((p.price*(1+(t.rate/100)))*(p.reduction_percent/100)))*cu.conversion_rate,2)
		ELSE
			ROUND((p.price*(1+t.rate/100))*cu.conversion_rate,2)
		END 
	END 
ELSE
	CASE WHEN p.reduction_from!=p.reduction_to THEN
		CASE WHEN p.reduction_to>='".date("Y-m-d")."' and p.reduction_from<='".date("Y-m-d")."' THEN
			CASE WHEN p.reduction_price>0 THEN
				ROUND((p.price-p.reduction_price)*cu.conversion_rate,2)
			WHEN p.reduction_percent>0 THEN
				ROUND((p.price-(p.price*(p.reduction_percent/100)))*cu.conversion_rate,2)
			ELSE
				ROUND((p.price)*cu.conversion_rate,2)
			END 
		ELSE
			ROUND((p.price)*cu.conversion_rate,2)
		END 	
	ELSE	
		CASE WHEN p.reduction_price>0 THEN
			ROUND((p.price-p.reduction_price)*cu.conversion_rate,2)
		WHEN p.reduction_percent>0 THEN
			ROUND((p.price-(p.price*(p.reduction_percent/100)))*cu.conversion_rate,2)
		ELSE
			ROUND((p.price)*cu.conversion_rate,2)
		END 			
	END 

END  ";
	
		$sql='SELECT DISTINCT p.id_product,0 as `new`, '.(((Tools::getValue('price_from')>0)||(Tools::getValue('price_to')>0))? $price_brutto.' as price_brutto,':'').' p.*, pl.`description_short`, pl.`available_now`, pl.`available_later`, pl.`link_rewrite`, pl.`name`, t.`rate`, i.`id_image`, il.`legend` 
		FROM `'._DB_PREFIX_.'product` p
		'.$attributes.$displaycategories.'
		'.(((Tools::getValue('price_from')>0)||(Tools::getValue('price_to')>0))? 'JOIN `'._DB_PREFIX_.'currency` cu ':'').'
		LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (p.`id_product` = pl.`id_product` AND pl.`id_lang` = '.intval($id_lang).')
		LEFT OUTER JOIN `'._DB_PREFIX_.'image` i ON (i.`id_product` = p.`id_product` AND i.`cover` = 1)
		LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (i.`id_image` = il.`id_image` AND il.`id_lang` = '.intval($id_lang).')
		LEFT JOIN `'._DB_PREFIX_.'tax` t ON p.`id_tax` = t.`id_tax` 		 
		WHERE p.`active` = 1 '.$searchbywords.' 
		'.(((Tools::getValue('price_from')>0)||(Tools::getValue('price_to')>0))? 'AND cu.id_currency='.intval($cookie->id_currency):'').' 
		'.((Tools::getValue('price_from')>0) ? 'AND '.$price_brutto.' >= '.intval((Tools::getValue('price_from'))) : '').'
		'.((Tools::getValue('price_to')>0) ? 'AND '.$price_brutto.' <= '.intval((Tools::getValue('price_to'))) : '').'
		'.$displaysupp.$displaymanuf.$attributes_val.'
		'.($orderBy ? ' ORDER BY '.$orderBy : '').($orderWay ? ' '.$orderWay : '').'
		LIMIT '.intval(($pageNumber - 1) * $pageSize).','.intval($pageSize);
		
		$sql_count='SELECT DISTINCT p.id_product,'.(((Tools::getValue('price_from')>0)||(Tools::getValue('price_to')>0))? $price_brutto.' as price_brutto,':'').' p.*, pl.`description_short`, pl.`available_now`, pl.`available_later`, pl.`link_rewrite`, pl.`name`, t.`rate`, i.`id_image`, il.`legend` 
		FROM `'._DB_PREFIX_.'product` p
		'.$attributes.$displaycategories.'
		'.(((Tools::getValue('price_from')>0)||(Tools::getValue('price_to')>0))? 'JOIN `'._DB_PREFIX_.'currency` cu ':'').'
		LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (p.`id_product` = pl.`id_product` AND pl.`id_lang` = '.intval($id_lang).')
		LEFT OUTER JOIN `'._DB_PREFIX_.'image` i ON (i.`id_product` = p.`id_product` AND i.`cover` = 1)
		LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (i.`id_image` = il.`id_image` AND il.`id_lang` = '.intval($id_lang).')
		LEFT JOIN `'._DB_PREFIX_.'tax` t ON p.`id_tax` = t.`id_tax` 		 
		WHERE p.`active` = 1 '.$searchbywords.' 
		'.(((Tools::getValue('price_from')>0)||(Tools::getValue('price_to')>0))? 'AND cu.id_currency='.intval($cookie->id_currency):'').' 
		'.((Tools::getValue('price_from')>0) ? 'AND '.$price_brutto.' >= '.intval((Tools::getValue('price_from'))) : '').'
		'.((Tools::getValue('price_to')>0) ? 'AND '.$price_brutto.' <= '.intval((Tools::getValue('price_to'))) : '').'
		'.$displaysupp.$displaymanuf.$attributes_val.'
		'.($orderBy ? ' ORDER BY '.$orderBy : '').($orderWay ? ' '.$orderWay : '');
		
		$result = Db::getInstance()->ExecuteS($sql);
		$result2 = Db::getInstance()->ExecuteS($sql_count);
		$total=count($result2);
		//$total = Db::getInstance()->getValue('SELECT FOUND_ROWS()');

		Module::hookExec('search', array('expr' => $expr, 'total' => $total));

		return array('total' => $total,'result' => Product::getProductsProperties($id_lang, $result));
	}
	/*-------------------------------advsearch---------------------------*/
	public static function categoriesLower($parent){
	
		
	$sql="SELECT c.* FROM "._DB_PREFIX_."category c WHERE c.id_parent=".intval($parent);
	$result=mysql_query($sql);
	$ile=mysql_num_rows($result);
	if($ile>0){
		while($row = mysql_fetch_array($result)){
			$display.= $row['id_category'].',';
			$display.=AdvSearch::categoriesLower($row['id_category']);
		}
	}	
	
	return $display; 
	}
	public static function queryCategories($cats){
		$categs='';
		if(!is_array($cats)){
			$cats=urldecode($cats);
			$pos = strpos($cats, ',');
				if ($pos !== false) {
					$cats =explode(",",$cats);
				}else{
					$cats=array($cats);
				}
		}
		
		if($cats[0]>0){
			foreach($cats as $key=>$val){
				$categs.=$val.',';
				$categs.= AdvSearch::categoriesLower($val);
				
			}
			
		}		
		$displaycats=explode(",",$categs);
		array_pop($displaycats);
		$displaycategories='INNER JOIN `'._DB_PREFIX_.'category_product` cp ON (p.id_product = cp.id_product ';
		if(count($displaycats) && $displaycats[0]>0){
			$i=0;
			$ile=count($displaycats);
			foreach($displaycats as $key=>$val){
				
				if((($ile-1)==$i)&&($i!=0)){
					$displaycategories.= ' || id_category ='.intval($val).') ';
				}elseif(((($ile-1)==$i))&&($i==0)){
					$displaycategories.= ' AND id_category='.intval($val).' ';					
				}else if(($ile>0)&&($i==0)){
					$displaycategories.= ' AND ( id_category ='.intval($val).' ';
				}else{
					$displaycategories.= ' || id_category ='.intval($val).' ';
				}												
			$i++;
			}
		}
		$displaycategories.=')';
	return $displaycategories;
	}
	public static function attributesQuery($lang,$id,$join=true){
		if($join==true){
			return 'LEFT JOIN (SELECT distinct pspa.id_product,p.id_attribute FROM '._DB_PREFIX_.'attribute p inner join '._DB_PREFIX_.'product_attribute_combination pspac on p.id_attribute=pspac.id_attribute left join '._DB_PREFIX_.'product_attribute pspa on pspa.id_product_attribute=pspac.id_product_attribute) z ON (p.`id_product` = z.`id_product`)';
		}else{
			$i=0;
			$ile=count($id);
			foreach ($id as $key=>$val){
				if(((($ile-1)==$i))&&($i!=0)){
					$display.= ' || z.id_attribute='.intval($val).') ';
				}elseif(((($ile-1)==$i))&&($i==0)){
					$display.= ' AND z.id_attribute='.intval($val).' ';
				}else if(($ile>0)&&($i==0)){
					$display.= ' AND ( z.id_attribute='.intval($val).' ';
				}else{
					$display.= ' || z.id_attribute ='.intval($val).' ';
				}												
			$i++;
			}
			return $display;
		}
	}
	public static function getSuppliers($suppliers,$table_val){
	$displaysupp='';
		if(is_array($suppliers)){
			if($suppliers[0]>0){
				$i=0;
				$ile=count($suppliers);
				foreach($suppliers as $key=>$val){
					if(((($ile-1)==$i))&&($i!=0)){
						$displaysupp.= ' || '.$table_val.'='.intval($val).') ';
					}elseif(((($ile-1)==$i))&&($i==0)){
						$displaysupp.= ' AND '.$table_val.'='.intval($val).' ';
					}else if(($ile>0)&&($i==0)){
						$displaysupp.= ' AND ( '.$table_val.'='.intval($val).' ';
					}else{
						$displaysupp.= ' || '.$table_val.' ='.intval($val).' ';
					}										
					$i++;	
				}		
			}
		}else{
			if($suppliers!=null){
				$pos = strpos($suppliers, ',');
				$pos2= strpos($suppliers, '%');
				
				if (is_numeric($suppliers)){
					return $displaysupp.= ' AND '.$table_val.' ='.intval($suppliers);
				}else{
					if($pos2 !==true){
					$suppliers=urldecode($suppliers);
					
					}
					$supp=explode(",",$suppliers);
					$i=0;
				$ile=count($supp);
				foreach($supp as $key=>$val){
					if(((($ile-1)==$i))&&($i!=0)){
						$displaysupp.= ' || '.$table_val.'='.intval($val).') ';
					}elseif(((($ile-1)==$i))&&($i==0)){
						$displaysupp.= ' AND '.$table_val.'='.intval($val).' ';
					}else if(($ile>0)&&($i==0)){
						$displaysupp.= ' AND ( '.$table_val.'='.intval($val).' ';
					}else{
						$displaysupp.= ' || '.$table_val.' ='.intval($val).' ';
					}										
					$i++;	
				}
				}
			}
		}
		
	return 	$displaysupp;
		
	
	}
}
	
?>
