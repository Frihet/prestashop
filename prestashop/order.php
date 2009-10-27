<?php

/* SSL Management */
$useSSL = true;

include_once(dirname(__FILE__).'/config/config.inc.php');
/* Step number is needed on some modules */
$step = intval(Tools::getValue('step'));
include_once(dirname(__FILE__).'/init.php');

/* Disable some cache related bugs on the cart/order */
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');

$errors = array();

/* Class FreeOrder to use PaymentModule (abstract class, cannot be instancied) */
class	FreeOrder extends PaymentModule {}

/* If some products have disappear */
if (!$cart->checkQuantities())
{
	$step = 0;
	$errors[] = Tools::displayError('An item in your cart is no longer available, you cannot proceed with your order');
}

/* Check minimal account */
$orderTotal = $cart->getOrderTotal();

$orderTotalDefaultCurrency = Tools::convertPrice($cart->getOrderTotal(true, 1), Currency::getCurrency(intval(Configuration::get('PS_CURRENCY_DEFAULT'))));
$minimalPurchase = floatval(Configuration::get('PS_PURCHASE_MINIMUM'));
if ($orderTotalDefaultCurrency < $minimalPurchase)
{
	$step = 0;
	$errors[] = Tools::displayError('A minimum purchase total of').' '.Tools::displayPrice($minimalPurchase, Currency::getCurrency(intval($cart->id_currency))).
	' '.Tools::displayError('is required in order to validate your order');
}

if (!$cart->nbProducts()) {
	$smarty->assign('empty', 1);
 	$step = 0;
}

global $order_pages_hook_position;
$order_pages_hook_titles = array();
$order_pages_hook_position = 0;

Module::hookExec('orderPages', array('part' => 'title'));
Module::hookExec('orderPages', array('part' => 'body', 'step' => intval($step)));

include(dirname(__FILE__).'/footer.php');

?>
