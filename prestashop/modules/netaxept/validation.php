<?php
	
	include(dirname(__FILE__).'/../../config/config.inc.php');
	include(dirname(__FILE__).'/../../header.php');
	include(dirname(__FILE__).'/netaxept.php');
	
	
	$errors 		= '';
	$result 		= false;
	$netaxept 		= new Netaxept();
	$mid_token 		= $netaxept->getMidToken($currency->iso_code);
	$token 			= $mid_token['token'];
	$merchant_id	= $mid_token['merchant_id'];
	$confirmed 		= false;
	$authorized		= false;
	$force			= false;
	$customer 		= new Customer(intval($cart->id_customer));
	$retry_url		= '<a href="'. __PS_BASE_URI__ .'order.php?step=6">'. $netaxept->l('Try again') .'</a>';

	
	//Continue only if BBSePay_transaction redir from BBS Terminal
	if (isset($_GET['BBSePay_transaction']))
	{
		$bbsepay_transaction 	= $_GET['BBSePay_transaction'];

		$params = array (
						"token"                 => "$token",
						"merchantId"            => "$merchant_id",
						"transactionString"     => "$bbsepay_transaction" );

		$client = new SoapClient($netaxept->getNetaxeptWsdlUrl(), array('trace' => true,'exceptions' => true ));

		try
		{
			$result = $client->__call('ProcessSetup' , array("parameters"=>$params));
			$process_setup_result = $result->ProcessSetupResult;
			$transaction_id = $process_setup_result->TransactionId;
			
			$params_auth = array (
								"token"			=> "$token",
								"merchantId"	=> "$merchant_id",
								"transactionId"	=> "$transaction_id" );
		
		}
		catch (SoapFault $fault) {
			print_r($fault->detail->BBSException->Result);
			if ($fault->detail->BBSException->Result->ResponseText == 'Auth Reg Failure') {
				$errors .= $netaxept->l('Payment failed, please contact your card issuer.');
			}
			$errors .= ' - '. $fault->detail->BBSException->Result->ResponseText;
		}

		try
		{
			$result = $client->__call('Auth', array("parameters"=>$params_auth));

		}
		catch (SoapFault $fault)
		{
			print_r($fault->detail->BBSException->Result);
			if ($fault->detail->BBSException->Result->ResponseText == 'Transaction already processed') {
				$force = true;
			} else {
				$errors .= ' - '. $fault->detail->BBSException->Result->ResponseText;
			}
		}

		//check if authorized
		$tq_wsdl = "https://epayment-test.bbs.no/TokenQuery.svc?wsdl";
		$tq_client = new SoapClient($tq_wsdl, array('trace' => true,'exceptions' => true ));
		try
		{
			$tq_result = $tq_client->__call('Query' , array("parameters"=>$params_auth));
			if ($tq_result->QueryResult->Summary->Authorized === true) {
				$authorized = true;
			}
		}
		catch (SoapFault $fault) {
			$errors .= ' - '. $fault->detail->BBSException->Result->ResponseText;
		}


		if ($authorized && (stristr($result->AuthResult->ResponseCode,"OK") || $force)) {
			$total = $cart->getOrderTotalLC(true, 3);
			$mail_vars = array();
			$netaxept->validateOrder($cart->id, _PS_OS_PAYMENT_, $total, $netaxept->displayName, NULL, $mail_vars, $currency->id);
			$confirmed = true;
		} else {
			echo $netaxept->l("Payment failed with error message: "). $errors;
			echo '<br />'. $retry_url;
		}
	} else {
		echo $netaxept->l('Error: No BBSePay_transaction. Payment cancelled.');
		echo '<br />'. $retry_url;
	}
	
	
	if ($confirmed) {
		$order = new Order($netaxept->currentOrder);
		Tools::redirectLink(__PS_BASE_URI__.'order-confirmation.php?id_cart='.$cart->id.'&id_module='.$netaxept->id.'&id_order='.$netaxept->currentOrder.'&key='.$order->secure_key);
	} 

	include(dirname(__FILE__).'/../../footer.php');

?>