<?php

try {
include(dirname(__FILE__).'/../../debug.php');
include(dirname(__FILE__).'/../../config/config.inc.php');
include(dirname(__FILE__).'/../../init.php');
include(dirname(__FILE__).'/../ordervendor/Vendor.php');
include(dirname(__FILE__).'/serialnrmanager.php');

if(intval(Configuration::get('PS_REWRITING_SETTINGS')) === 1)
	$rewrited_url = __PS_BASE_URI__;

include(dirname(__FILE__).'/../../header.php');
/* include(dirname(__FILE__).'/../../debug-style.php'); */

require_once(dirname(__FILE__)."/ProductInstance.php");

$instances = ProductInstance::search($_GET['serial'], $cookie->id_lang);
$instance = $instances[0];

if (Vendor::currentVendor() != null || $cookie->id_customer == $instance['id_current_owner']) {
 if (isset($_POST['new_owner_email'])) {
  $new_customer = new Customer;
  $new_customer->getByEmail($_POST['new_owner_email']);
  if ($new_customer->id != 0) {
   $obj = new ProductInstance($instance['id_product_instance']);
   $obj->id_current_owner = $new_customer->id;
   $obj->save();
   Tools::redirect($_SERVER['REQUEST_URI'], '');
  } else {
   $errors[] = Tools::displayError('No customer is registered with that email address.');
  }
 }
} else {
   $errors[] = Tools::displayError('Please refer to your vendor to change owner.');
}

$smarty->assign('errors', $errors);
$smarty->assign('product_instance', $instance);

$manager = new SerialNrManager();
echo $manager->display(dirname(__FILE__).'/serialnrmanager.php', 'changeowner.tpl');

include(dirname(__FILE__).'/../../footer.php');

} catch (Exception $e) {
 echo $e->getMessage();
 echo "<br><bR>";
 foreach ($e->getTrace() as $line) {
  echo "{$line['class']}.{$line['function']} @ {$line['file']}:{$line['line']}<br>";
 }
}

?>
