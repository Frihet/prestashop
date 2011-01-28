<?php
	include(dirname(__FILE__).'/../../config/config.inc.php');
	include(dirname(__FILE__).'/../../header.php');
	include(dirname(__FILE__).'/netaxept.php');


	function netaxept_validation() {
		
		global $cart;
		global $currency;
		
		$err_msg 		= '';
		$result 		= false;
		$netaxept 		= new Netaxept();
		$mid_token 		= $netaxept->getMidToken($currency->iso_code);
		$token 			= $mid_token['token'];
		$merchant_id	= $mid_token['merchant_id'];
		$confirmed 		= false;
		$authorized		= false;
		$force			= false;
		$customer 		= new Customer(intval($cart->id_customer));
		$retry_url		= '<a href="'. __PS_BASE_URI__ .'order.php?step=1">'. $netaxept->l('Try again') .'</a>';
		$error_99		= 'Payment failed, please contact your card issuer.';
		$error_17		= 'The transaction was cancelled by you.';
	
		
		//Continue only if BBSePay_transaction redir from BBS Terminal
		if (isset($_GET['BBSePay_transaction']))
		{
			$bbsepay_transaction 	= $_GET['BBSePay_transaction'];
	
			$params = array (
							"token"                 => "$token",
							"merchantId"            => "$merchant_id",
							"transactionString"     => "$bbsepay_transaction" );
	
			$client = new SoapClient($netaxept->getNetaxeptWsdlUrl(), array('trace' => true,'exceptions' => true ));
	
			//process setup
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
/*				print_r($fault);
				die();*/
				//user cancelled
				if ($fault->detail->UserCancelledException->Result->ResponseCode == 17) {
					$err_msg = $error_17;
				}
				//processsetup failed
				elseif ($fault->detail->BBSException->Result->ResponseText == 'Auth Reg Failure') {
					$err_msg = $error_99;
				}
			}
	
			if ($err_msg == '') {
				//authorize the transaction
				try
				{
					$result = $client->__call('Auth', array("parameters"=>$params_auth));
		
				}
				catch (SoapFault $fault)
				{
					if ($fault->detail->BBSException->Result->ResponseText == 'Transaction already processed') {
						$force = true;
					} else {
						$err_msg = $error_99;
					}
				}
			}
	
			//check if authorized
			$tq_wsdl = $netaxept->getNetaxeptTqWsdlUrl();
			$tq_client = new SoapClient($tq_wsdl, array('trace' => true,'exceptions' => true ));
			if ($err_msg == '') {
				try
				{
					$tq_result = $tq_client->__call('Query' , array("parameters"=>$params_auth));
					if ($tq_result->QueryResult->Summary->Authorized === true) {
						$authorized = true;
					}
				}
				catch (SoapFault $fault) {
					$err_msg = $error_99;
				}
			}
	
			if ($authorized && (stristr($result->AuthResult->ResponseCode, "OK") || $force)) {
				$total = $cart->getOrderTotalLC(true, 3);
				$mail_vars = array();
				$netaxept->validateOrder($cart->id, _PS_OS_PAYMENT_, $total, $netaxept->displayName, NULL, $mail_vars, $currency->id, false, $transaction_id);
				$confirmed = true;
			} else {
				echo $netaxept->l($err_msg);
				echo '<br />'. $retry_url;
			}
		} else {
			echo $netaxept->l('Payment error.');
			echo '<br />'. $retry_url;
		}
		
		
		if ($confirmed) {
			$order = new Order($netaxept->currentOrder);
			Tools::redirectLink(__PS_BASE_URI__.'order-confirmation.php?id_cart='.$cart->id.'&id_module='.$netaxept->id.'&id_order='.$netaxept->currentOrder.'&key='.$order->secure_key);
		} 
	}
	
	netaxept_validation();


	include(dirname(__FILE__).'/../../footer.php');
?>
