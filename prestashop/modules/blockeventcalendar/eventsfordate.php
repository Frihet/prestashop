<?php

try {

include(dirname(__FILE__).'/../../debug.php');
include(dirname(__FILE__).'/../../config/config.inc.php');
include(dirname(__FILE__).'/../../init.php');
include(dirname(__FILE__).'/blockeventcalendar.php');

//will be initialized bellow...
if(intval(Configuration::get('PS_REWRITING_SETTINGS')) === 1)
	$rewrited_url = null;

/* CSS ans JS files calls */
$css_files = array(__PS_BASE_URI__.'css/jquery.cluetip.css' => 'all', _THEME_CSS_DIR_.'scenes.css' => 'all');

include(dirname(__FILE__).'/../../header.php');

require_once(dirname(__FILE__).'/EventSearch.php');

if ($event_date = Tools::getValue('event_date') AND !is_array($event_date))
{
	$n = abs(intval(Tools::getValue('n', Configuration::get('PS_PRODUCTS_PER_PAGE'))));
	$p = abs(intval(Tools::getValue('p', 1)));
	$search = EventSearch::find_event(intval($cookie->id_lang), $event_date,  $p, $n);
	$nbProducts = $search['total'];

	include(dirname(__FILE__).'/../../pagination.php');

	$smarty->assign(array('products' => $search['result'], 'nbProducts' => $search['total'], 'event_date' => $event_date));
}
else
{
	$smarty->assign(array(
	'products' => array(),
	'pages_nb' => 1,
	'nbProducts' => 0));
}

function HOOK_PRODUCT_LIST_ACTIONS($params) {
 global $product;

 $product = $params['product'];
 return Module::hookExec('productListActions');
}

if (!isset($_GET['id_category'])) $_GET['id_category'] = 0;
if (!isset($_GET['id_manufacturer'])) $_GET['id_manufacturer'] = 0;
if (!isset($_GET['id_supplier'])) $_GET['id_supplier'] = 0;

$smarty->register_function("HOOK_PRODUCT_LIST_ACTIONS", "HOOK_PRODUCT_LIST_ACTIONS");

$block = new BlockEventCalendar();
echo $block->display(dirname(__FILE__).'/blockeventcalendar.php', 'eventsfordate.tpl');

include(dirname(__FILE__).'/../../footer.php');

} catch (Exception $e) {
 echo $e->getMessage();
 echo "<br><bR>";
 foreach ($e->getTrace() as $line) {
  echo "{$line['class']}.{$line['function']} @ {$line['file']}:{$line['line']}<br>";
 }
}

?>
