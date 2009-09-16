<?php

global $cookie;

//call module
include(dirname(__FILE__).'/blockcompare.php');
$compare = new Compare(intval($cookie->id_compare));
$compare->id_lang = intval($cookie->id_lang);
$hookArgs = array();

$hookArgs['cookie'] = $cookie;
$hookArgs['compare'] = $compare;

//show module
$blockCompare = new BlockCompare();

echo $blockCompare->hookAjaxCall($hookArgs);
?>