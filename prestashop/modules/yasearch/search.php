<?php

include(dirname(__FILE__).'/config/config.inc.php');

if (Tools::getValue('ajaxSearch') AND $query = urldecode(Tools::getValue('q')) AND !is_array($query))
{
	include(dirname(__FILE__).'/init.php');
	$link = new Link();
	$search = Search::find(intval(Tools::getValue('id_lang')), $query, 1, 10, 'position', 'desc', true);
	foreach ($search as $product)
		echo $product['id_product'].'|'.$product['pname'].'|'.$product['cname'].'|'.$link->getProductLink($product['id_product'], $product['prewrite'], $product['crewrite'])."\n";
	die;
}

include(dirname(__FILE__).'/header.php');
include(dirname(__FILE__).'/product-sort.php');
if ((Tools::getValue('advSearch')==1))
{
	if(((Tools::getValue('search_queryy')==null)
	AND(Tools::getValue('price_from')==null)
	AND(Tools::getValue('price_to')==null)
	AND(!(Tools::getValue('atrybut_opcja')))
	AND(!(Tools::getValue('categories')))
	AND(!(Tools::getValue('supply')))
	AND(!(Tools::getValue('manufacturer')))
	AND(!(Tools::getValue('tags'))))
	OR (!Validate::isValidSearch((Tools::getValue('search_queryy'))))
	OR ((Tools::getValue('price_from')!=null)AND(!is_numeric((Tools::getValue('price_from')))))
	OR ((Tools::getValue('price_to')!=null)AND(!is_numeric((Tools::getValue('price_to')))))
	)
	{
		$smarty->assign('errors', array(Tools::displayError('invalid search')));
	}else{
		
		$n = abs(intval(Tools::getValue('n', Configuration::get('PS_PRODUCTS_PER_PAGE'))));
		$p = abs(intval(Tools::getValue('p', 1)));
		
		$search = Search::find_adv(intval($cookie->id_lang),Tools::getValue('search_queryy'), $p, $n, $orderBy,$orderWay);
		$nbProducts = $search['total'];
		include(dirname(__FILE__).'/pagination.php');
		/*$smarty->assign(array(
			'products' => $search->find_adv(intval($cookie->id_lang),Tools::getValue('search_queryy'), $p, $n, $orderBy,$orderWay),
			'nbProducts' => $nbProducts,
			'advSearch' => 'Advanced Search'));
		*/
		$smarty->assign(array('products' => $search['result'], 'nbProducts' => $search['total'], 'query' => 'Advanced Search'));
	}
	
	
}else{
if ($query = Tools::getValue('search_query', Tools::getValue('tag', Tools::getValue('ref'))) AND !is_array($query))
{
	$n = abs(intval(Tools::getValue('n', Configuration::get('PS_PRODUCTS_PER_PAGE'))));
	$p = abs(intval(Tools::getValue('p', 1)));
	$search = Search::find(intval($cookie->id_lang), $query, $p, $n, $orderBy, $orderWay);
	$nbProducts = $search['total'];
	include(dirname(__FILE__).'/pagination.php');
	$smarty->assign(array('products' => $search['result'], 'nbProducts' => $search['total'], 'query' => $query));
}
else
{
	$smarty->assign(array(
	'products' => array(),
	'pages_nb' => 1,
	'nbProducts' => 0));
}
}
$smarty->display(_PS_THEME_DIR_.'search.tpl');

include(dirname(__FILE__).'/footer.php');

?>