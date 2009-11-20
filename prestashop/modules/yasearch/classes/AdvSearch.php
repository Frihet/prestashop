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

class AdvSearch extends Search
{
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
		$price_sql = str_replace('PREFIX_', _DB_PREFIX_, Product::getProductPriceSql('p.id_product', 'pp'));
	
		$sql='SELECT DISTINCT p.id_product,0 as `new`, '.(((Tools::getValue('price_from')>0)||(Tools::getValue('price_to')>0))? $price_brutto.' as price_brutto,':'').' p.*, pl.`description_short`, pl.`available_now`, pl.`available_later`, pl.`link_rewrite`, pl.`name`, t.`rate`, i.`id_image`, il.`legend`, pp.*
		FROM `'._DB_PREFIX_.'product` p
		'.$attributes.$displaycategories.'
		'.(((Tools::getValue('price_from')>0)||(Tools::getValue('price_to')>0))? 'JOIN `'._DB_PREFIX_.'currency` cu ':'').'
		LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (p.`id_product` = pl.`id_product` AND pl.`id_lang` = '.intval($id_lang).')
		LEFT OUTER JOIN `'._DB_PREFIX_.'image` i ON (i.`id_product` = p.`id_product` AND i.`cover` = 1)
		LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (i.`id_image` = il.`id_image` AND il.`id_lang` = '.intval($id_lang).')
		'.$price_sql.'
		LEFT JOIN `'._DB_PREFIX_.'tax` t ON pp.`id_tax` = t.`id_tax` 		 
		WHERE p.`active` = 1 '.$searchbywords.' 
		'.(((Tools::getValue('price_from')>0)||(Tools::getValue('price_to')>0))? 'AND cu.id_currency='.intval($cookie->id_currency):'').' 
		'.((Tools::getValue('price_from')>0) ? 'AND '.$price_brutto.' >= '.intval((Tools::getValue('price_from'))) : '').'
		'.((Tools::getValue('price_to')>0) ? 'AND '.$price_brutto.' <= '.intval((Tools::getValue('price_to'))) : '').'
		'.$displaysupp.$displaymanuf.$attributes_val.'
		'.($orderBy ? ' ORDER BY '.$orderBy : '').($orderWay ? ' '.$orderWay : '').'
		LIMIT '.intval(($pageNumber - 1) * $pageSize).','.intval($pageSize);
		
		$sql_count='SELECT DISTINCT p.id_product,'.(((Tools::getValue('price_from')>0)||(Tools::getValue('price_to')>0))? $price_brutto.' as price_brutto,':'').' p.*, pl.`description_short`, pl.`available_now`, pl.`available_later`, pl.`link_rewrite`, pl.`name`, t.`rate`, i.`id_image`, il.`legend`, pp.* 
		FROM `'._DB_PREFIX_.'product` p
		'.$attributes.$displaycategories.'
		'.(((Tools::getValue('price_from')>0)||(Tools::getValue('price_to')>0))? 'JOIN `'._DB_PREFIX_.'currency` cu ':'').'
		LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (p.`id_product` = pl.`id_product` AND pl.`id_lang` = '.intval($id_lang).')
		LEFT OUTER JOIN `'._DB_PREFIX_.'image` i ON (i.`id_product` = p.`id_product` AND i.`cover` = 1)
		LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (i.`id_image` = il.`id_image` AND il.`id_lang` = '.intval($id_lang).')
		'.$price_sql.'
		LEFT JOIN `'._DB_PREFIX_.'tax` t ON pp.`id_tax` = t.`id_tax` 		 
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
