<?php

global $cookie;

//call module
require_once(dirname(__FILE__).'/blockcompare.php');
$compare = new CompareCart(intval($cookie->id_compare));
$compare->id_lang = intval($cookie->id_lang);
$hookArgs = array();

$hookArgs['cookie'] = $cookie;
$hookArgs['compare'] = $compare;

//show module
$blockCompare = new BlockCompare();

echo $blockCompare->hookAjaxCall($hookArgs);
?>
