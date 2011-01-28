<?php

include(dirname(__FILE__).'/../../config/config.inc.php');
include(dirname(__FILE__).'/dibs.php');

$errors = '';
$dibs = new Dibs();

$params = 'cmd=_notify-validate';
foreach ($_POST AS $key => $value)
	$params .= '&'.$key.'='.urlencode(stripslashes($value));

$header = 'POST /cgi-bin/webscr HTTP/1.0'."\r\n" .
          'Host: '.dibsServer."\r\n".
          'Content-Type: application/x-www-form-urlencoded'."\r\n".
          'Content-Length: '.Tools::strlen($params)."\r\n".
          'Connection: close'."\r\n\r\n";

	$trans_id = $_POST['transact'];
	$amount = $_POST['amount'];
	$fee = $_POST['fee'];
	$dibs_cur = $_POST['currency'];
	$authkey  = $_POST['authkey'];
	$order_id = $_POST['orderid'];
	
	$md5key=md5(Configuration::get('DIBS_KEY2').md5(Configuration::get('DIBS_KEY1').'transact='.$trans_id.'&amount='.$amount.'&currency='.$dibs_cur));
			if ($authkey == $md5key) {
			 $verified = true;
			}
			else
			{$verified = false;}
	
	if ($verified)
	{
	 $amount = $amount / 100;
		if (!isset($_POST['transact'])){
			$errors .= $dibs->getL('transact').'<br />';}
		if (!isset($_POST['amount'])){
			$errors .= $dibs->getL('amo8unt').'<br />';}
		if (!isset($_POST['authkey'])){
			$errors .= $dibs->getL('authkey').'<br />';}
		if (!isset($_POST['orderid'])){
			$errors .= $dibs->getL('orderid').'<br />';}
		if (!isset($_POST['currency'])){
			$errors .= $dibs->getL('currency').'<br />';}
		if (empty($errors))
		{
			$cart = new Cart(intval($_POST['orderid']));
			if (!$cart->id)
				$errors = $dibs->getL('orderid').'<br />';
			elseif (Order::getOrderByCartId(intval($_POST['orderid'])))
				$errors = $dibs->getL('carterror').'<br />';
			else
				$dibs->validateOrder($_POST['orderid'], _PS_OS_PAYMENT_, $amount, $dibs->displayName, $dibs->getL('transaction').$_POST['transact']);
		}
	}
	else
	{
		$errors .= $dibs->getL('verified').'<br />'.$read;}

if (!empty($errors) AND isset($_POST['orderid'])){
	$dibs->validateOrder(intval($_POST['orderid']), _PS_OS_ERROR_, 0, $dibs->displayName, $errors);
	}
?>