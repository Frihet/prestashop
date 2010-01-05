<?php

error_reporting(E_ALL);



class Netaxept extends PaymentModule
{
	private	$_html = '';
	private $_postErrors = array();

	public function __construct()
	{
		$this->name = 'netaxept';
		$this->tab = 'Payment';
		$this->version = '1.0';
		
		$this->currencies = true;
		$this->currencies_mode = 'checkbox';

        parent::__construct();
			
		$this->page = basename(__FILE__, '.php');
		$this->displayName = $this->l('Netaxept');
		$this->description = $this->l('Accepts payments by Netaxept');
		$this->confirmUninstall = $this->l('Are you sure you want to delete your details ?');
	}
	
	
	
	public function getNetaxeptTerminalUrl()
	{
		return Configuration::get('NETAXEPT_TERMINAL_URL');
	}
	
	
	
	public function getNetaxeptWsdlUrl()
	{
		return Configuration::get('NETAXEPT_WSDL_URL');
	}
	
	
	
	public function getNetaxeptTqWsdlUrl()
	{
		return Configuration::get('NETAXEPT_TQ_WSDL_URL');
	}



	public function install()
	{
		if (!parent::install()
			OR !Configuration::updateValue('NETAXEPT_MID_TOKEN_CURR', '10000327;5e_R-Zp9;NOK')
			OR !Configuration::updateValue('NETAXEPT_TERMINAL_URL', 'https://epayment-test.bbs.no/terminal/default.aspx')
			OR !Configuration::updateValue('NETAXEPT_WSDL_URL', 'https://epayment-test.bbs.no/service.svc?wsdl')
			OR !Configuration::updateValue('NETAXEPT_TQ_WSDL_URL', 'https://epayment-test.bbs.no/TokenQuery.svc?wsdl')
			OR !$this->registerHook('payment')
			OR !$this->registerHook('paymentReturn'))
			return false;

		return true;
	}



	public function uninstall()
	{
		if (!Configuration::deleteByName('NETAXEPT_MID_TOKEN_CURR')
			OR !Configuration::deleteByName('NETAXEPT_TERMINAL_URL')
			OR !Configuration::deleteByName('NETAXEPT_WSDL_URL')
			OR !Configuration::deleteByName('NETAXEPT_TQ_WSDL_URL')
			OR !parent::uninstall())
			return false;

		return true;
	}



	public function getContent()
	{
		//$this->_html = '<h2>'.$this->displayName.'</h2>';
		$this->_html = '<h2>Netaxept</h2>';
		if (isset($_POST['submitNetaxept']))
		{
				Configuration::updateValue('NETAXEPT_MID_TOKEN_CURR', strval($_POST['mid_token_curr']));
				Configuration::updateValue('NETAXEPT_TERMINAL_URL', strval($_POST['terminal_url']));
				Configuration::updateValue('NETAXEPT_WSDL_URL', strval($_POST['wsdl_url']));
				Configuration::updateValue('NETAXEPT_TQ_WSDL_URL', strval($_POST['tq_wsdl_url']));
				$this->displayConf();
		} else {
			$this->displayErrors();
		}

		$this->displayNetaxept();
		$this->displayFormSettings();
		
		return $this->_html;
	}

	public function displayConf()
	{
		$this->_html .= '
		<div class="conf confirm">
			<img src="../img/admin/ok.gif" alt="'.$this->l('Confirmation').'" />
			'.$this->l('Settings updated').'
		</div>';
	}

	public function displayErrors()
	{
		$nbErrors = sizeof($this->_postErrors);
		if ($nbErrors > 0) {
			$this->_html .= '
			<div class="alert error">
				<h3>'.($nbErrors > 1 ? $this->l('There are') : $this->l('There is')).' '.$nbErrors.' '.($nbErrors > 1 ? $this->l('errors') : $this->l('error')).'</h3>
				<ol>';
			foreach ($this->_postErrors AS $error)
				$this->_html .= '<li>'.$error.'</li>';
			$this->_html .= '
				</ol>
			</div>';
		}
	}
	
	
	public function displayNetaxept()
	{
		$this->_html .= '
		<!-- div style="float: right; width: 440px; height: 150px; border: dashed 1px #666; padding: 8px; margin-left: 12px;">
			<h2>'.$this->l('Netaxept').'</h2>
			<div style="clear: both;"></div>
			<p>'.$this->l('').'</p>
			<div style="clear: right;"></div>
		</div -->
		<img src="../modules/netaxept/netaxept.gif" style="float:left; margin-right:15px;" />
		<b>'.$this->l('This module allows you to accept payments by Netaxept.').'</b><br /><br />
		'.$this->l('You need to have an agreement with your bank before using this module.').'
		<br /><br /><br /><br />
		<div style="clear:both;">&nbsp;</div>';
	}

	public function displayFormSettings()
	{
		$conf = Configuration::getMultiple(array('NETAXEPT_MID_TOKEN_CURR', 'NETAXEPT_TERMINAL_URL', 'NETAXEPT_WSDL_URL', 'NETAXEPT_TQ_WSDL_URL'));
		$mid_token_curr = array_key_exists('uid', $_POST) ? $_POST['mid_token_curr'] : (array_key_exists('NETAXEPT_MID_TOKEN_CURR', $conf) ? $conf['NETAXEPT_MID_TOKEN_CURR'] : '');
		$terminal_url = array_key_exists('terminal_url', $_POST) ? $_POST['terminal_url'] : (array_key_exists('NETAXEPT_TERMINAL_URL', $conf) ? $conf['NETAXEPT_TERMINAL_URL'] : '');
		$wsdl_url = array_key_exists('wsdl_url', $_POST) ? $_POST['wsdl_url'] : (array_key_exists('NETAXEPT_WSDL_URL', $conf) ? $conf['NETAXEPT_WSDL_URL'] : '');
		$tq_wsdl_url = array_key_exists('tq_wsdl_url', $_POST) ? $_POST['tq_wsdl_url'] : (array_key_exists('NETAXEPT_TQ_WSDL_URL', $conf) ? $conf['NETAXEPT_TQ_WSDL_URL'] : '');


		$this->_html .= '
		<form action="'.$_SERVER['REQUEST_URI'].'" method="post" style="clear: both;">
		<fieldset>
			<legend><img src="../img/admin/contact.gif" />'.$this->l('Settings').'</legend>
			<label>'.$this->l('Netaxept MerchantID, Token and Currency').'</label>
			<div class="margin-form">
				'.$this->l('Only valid format: MerchantID;Token;Currency.<br />To add several currencies type each MerchantID;Token;Currency in a seperate line.').'
				<textarea rows="5" cols="40" name="mid_token_curr">'.htmlentities($mid_token_curr, ENT_COMPAT, 'UTF-8').'</textarea>
			</div>
			<label>'.$this->l('Netaxept Terminal URL').'</label>
			<div class="margin-form"><input type="text" size="60" name="terminal_url" value="'.htmlentities($terminal_url, ENT_COMPAT, 'UTF-8').'" /></div>
			<label>'.$this->l('Netaxept WSDL URL').'</label>
			<div class="margin-form"><input type="text" size="60" name="wsdl_url" value="'.htmlentities($wsdl_url, ENT_COMPAT, 'UTF-8').'" /></div>
			<label>'.$this->l('Netaxept TokenQuery WSDL URL').'</label>
			<div class="margin-form"><input type="text" size="60" name="tq_wsdl_url" value="'.htmlentities($tq_wsdl_url, ENT_COMPAT, 'UTF-8').'" /></div>
			<br /><br /><br />
			<center><input type="submit" name="submitNetaxept" value="'.$this->l('Update settings').'" class="button" /></center>
		</fieldset>
		</form><br /><br />
		<fieldset class="width3">
			<legend><img src="../img/admin/warning.gif" />'.$this->l('Information').'</legend>
			'.$this->l('To test payments go to ').
				'<a href="https://epayment-test.bbs.no/admin/Help/Reference.aspx" target="_blank">https://epayment-test.bbs.no/admin/Help/Reference.aspx</a>'
				.$this->l(' to get test card numbers.').'<br /><br />
				<strong>'. $this->l('Netaxept test and production domains') .'</strong><br />
				- '.$this->l('Test').': https://epayment-test.bbs.no/<br />
				- '.$this->l('Production').': https://epayment.bbs.no/
				<br /><br />
		</fieldset>';
	}
	
	
	//get merchant id
	public function getMidToken($currency_iso_code)
	{
		$mid_token_curr = Configuration::get('NETAXEPT_MID_TOKEN_CURR');
		$arr = explode("\n", $mid_token_curr);

		foreach ($arr as $val)
		{
			if (stristr($val, ';'.$currency_iso_code))
			{
				$ex = explode(';', $val);
				return array('merchant_id' => $ex[0], 'token' => $ex[1]);
			}
		}

		return array('merchant_id' => '', 'token' => '');
	}


	public function hookPayment($params)
	{
		if (!$this->active)
			return;

		global $smarty, $cart, $currency;

		$wsdl_url 				= $this->getNetaxeptWsdlUrl();
		
		$currency_iso_code 		= $currency->iso_code;
		$mid_token				= $this->getMidToken($currency_iso_code);
		$token 					= $mid_token['token'];
		$merchant_id			= $mid_token['merchant_id'];

		$netaxept_url 			= Configuration::get('NETAXEPT_TERMINAL_URL');
		$redirect_url 			= 'http://'.htmlspecialchars($_SERVER['HTTP_HOST'], ENT_COMPAT, 'UTF-8').__PS_BASE_URI__.'modules/netaxept/validation.php';
		$service_type			= 'B'; //B = BBS hosted terminal
		$transaction_id			= md5(uniqid(rand(), true));
		$amount					= round($cart->getOrderTotalLC(), 2) * 100;
		$order_number			= intval($params['cart']->id);

		switch ($currency_iso_code) {
			case 'NOK':
				$language == 'no_NO';
				break;
			case 'SEK':
				$language == 'sv_SE';
				break;
			default:
				$language == 'en_GB';
		}

		$customer = new Customer(intval($params['cart']->id_customer));
		$customer_email 		= $customer->email;
		$customer_phone_number 	= $customer->id;
		
		$c = 0;
		foreach ($params['cart']->getProducts() as $arr) {
			$order_description .= ($c++ > 0) ? ', '. $arr['name'] : $arr['name'];
		}
		$description 			= $order_description;
		$description 			= substr($description, 0, 4000); // length limit 4000 chars
		$order_description 		= substr($order_description, 0, 1500); // length limit 1500 chars
		
		$pan_hash 				= '';
		$recurring_expiry_date 	= '';
		$recurring_frequency 	= '';
		$recurring_type			= '';
		$service_type 			= '';
		$session_id				= '';
		
		include(dirname(__FILE__).'/setup_request.class.php');

		$setup_request = new SetupRequest (
				$amount, $currency_iso_code, $customer_email, $customer_phone_number,
				$description, $language, $order_description, $order_number,
				$pan_hash, $recurring_expiry_date, $recurring_frequency,  $recurring_type,
				$redirect_url, $service_type, $session_id, $transaction_id );

		$params_transaction = array (
				"token"			=> "$token",
				"merchantId"	=> "$merchant_id",
				"request"		=> $setup_request );
		$client = new SoapClient($wsdl_url, array('trace' => true,'exceptions' => true ));
		$result = $client->__call('Setup' , array("parameters" => $params_transaction));
		$setup_result = $result->SetupResult;

		$smarty->assign(array(
							  'setup_result' => $setup_result,
							  'netaxept_url' => $netaxept_url));

		return $this->display(__FILE__, 'netaxept.tpl');
	}

	public function hookPaymentReturn($params)
	{
		if (!$this->active)
			return ;
		
		return $this->display(__FILE__, 'confirmation.tpl');
	}
}
