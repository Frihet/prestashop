<?php

include(dirname(__FILE__).'/../../debug.php');
require_once(dirname(__FILE__).'/../../config/config.inc.php');
require_once(dirname(__FILE__).'/../../init.php');
require_once(dirname(__FILE__).'/blockcompare.php');

$errors = array();

Tools::safePostVars();

include_once(dirname(__FILE__).'/../../header.php');
include(dirname(__FILE__).'/../../debug-style.php');

$m = new BlockCompare();
$m->hookTop(array());

$products = $compare_cart->getProducts();
$smarty->assign(array('products' => $products));

$features = array();
foreach ($products as $product) {
  $product = new Product($product['id']);

  $product_features = $product->getFrontFeatures(intval($cookie->id_lang));
  foreach ($product_features as $product_feature) {
    if (!isset($features[$product_feature['name']]))
      $features[$product_feature['name']] = array();
    $features[$product_feature['name']][$product->id] = $product_feature['value'];
  }
}
$smarty->assign(array('features' => $features));

$block = new BlockCompare();
echo $block->display(dirname(__FILE__).'/blockcompare.php', 'comparecart.tpl');

include_once(dirname(__FILE__).'/../../footer.php');
