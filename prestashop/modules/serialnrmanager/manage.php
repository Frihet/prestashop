<?php

try {
include(dirname(__FILE__).'/../../debug.php');
include(dirname(__FILE__).'/../../config/config.inc.php');
include(dirname(__FILE__).'/../../init.php');

if(intval(Configuration::get('PS_REWRITING_SETTINGS')) === 1)
	$rewrited_url = __PS_BASE_URI__;

include(dirname(__FILE__).'/../../header.php');
/* include(dirname(__FILE__).'/../../debug-style.php'); */

require_once(dirname(__FILE__)."/ProductInstance.php");

if (!isset($_GET['serial'])) $_GET['serial'] = '';
$smarty->assign('serial', $_GET['serial']);
if ($_GET['serial'] != '') {
 $smarty->assign('product_instances', ProductInstance::search($_GET['serial'], $cookie->id_lang));
}
$smarty->display(dirname(__file__).'/manage.tpl');

include(dirname(__FILE__).'/../../footer.php');

} catch (Exception $e) {
 echo $e->getMessage();
 echo "<br><bR>";
 foreach ($e->getTrace() as $line) {
  echo "{$line['class']}.{$line['function']} @ {$line['file']}:{$line['line']}<br>";
 }
}

?>
