<?php
class Dibs extends PaymentModule
{
	private	$_html = '';
	private $_postErrors = array();

	public function __construct()
	{
		$this->name = 'dibs';
		$this->tab = 'Payment';
		$this->version = '1.2';

        parent::__construct();

        /* The parent construct is required for translations */
		$this->page = basename(__FILE__, '.php');
	        $this->displayName = $this->l('Dibs');
	        $this->description = $this->l('Accepts payments by Dibs');
		$this->confirmUninstall = $this->l('Are you sure you want to delete your details ?');
	}
	
	var $currency_iso4217code=array('AFN'=>'971','ALL'=>'8','AMD'=>'51','ANG'=>'532','AOA'=>'973','ARS'=>'32','AUD'=>'36','AWG'=>'533','AZN'=>'944','BAM'=>'977','BBD'=>'52','BDT'=>'50','BGN'=>'975','BHD'=>'48','BIF'=>'108','BMD'=>'60','BND'=>'96','BOB'=>'68','BOV'=>'984','BRL'=>'986','BSD'=>'44','BTN'=>'64','BWP'=>'72','BYR'=>'974','BZD'=>'84','CAD'=>'124','CDF'=>'976','CHE'=>'947','CHF'=>'756','CHW'=>'948','CLF'=>'990','CLP'=>'152','CNY'=>'156','COP'=>'170','COU'=>'970','CRC'=>'188','CUP'=>'192','CVE'=>'132','CYP'=>'196','CZK'=>'203','DJF'=>'262','DKK'=>'208','DOP'=>'214','DZD'=>'12','EEK'=>'233','EGP'=>'818','ERN'=>'232','ETB'=>'230','EUR'=>'978','FJD'=>'242','FKP'=>'238','GBP'=>'826','GEL'=>'981','GHS'=>'288','GIP'=>'292','GMD'=>'270','GNF'=>'324','GTQ'=>'320','GYD'=>'328','HKD'=>'344','HNL'=>'340','HRK'=>'191','HTG'=>'332','HUF'=>'348','IDR'=>'360','ILS'=>'376','INR'=>'356','IQD'=>'368','IRR'=>'364', 'ISK'=>'352','JMD'=>'388','JOD'=>'400','JPY'=>'392','KES'=>'404','KGS'=>'417','KHR'=>'116','KMF'=>'174','KPW'=>'408','KRW'=>'410','KWD'=>'414','KYD'=>'136', 'KZT'=>'398','LAK'=>'418','LBP'=>'422','LKR'=>'144','LRD'=>'430','LSL'=>'426','LTL'=>'440','LVL'=>'428','LYD'=>'434','MAD'=>'504','MDL'=>'498','WST'=>'882','MGA'=>'969','MKD'=>'807','MMK'=>'104','MNT'=>'496','MOP'=>'446','MRO'=>'478','MTL'=>'470','MUR'=>'480','MVR'=>'462','MWK'=>'454','MXN'=>'484','MXV'=>'979','MYR'=>'458','MZN'=>'943','NAD'=>'516','NGN'=>'566','NIO'=>'558','NOK'=>'578','NPR'=>'524','NZD'=>'554','OMR'=>'512','PAB'=>'590','PEN'=>'604','PGK'=>'598','PHP'=>'608','PKR'=>'586','PLN'=>'985','PYG'=>'600','QAR'=>'634','RON'=>'946','RSD'=>'941','RUB'=>'643','RWF'=>'646','SAR'=>'682','SBD'=>'90','SCR'=>'690','SDG'=>'938','SEK'=>'752','SGD'=>'702','SHP'=>'654','SKK'=>'703','SLL'=>'694','SOS'=>'706','SRD'=>'968','STD'=>'678','SYP'=>'760','SZL'=>'748','USN'=>'997','THB'=>'764','TJS'=>'972','TMM'=>'795','TND'=>'788','TOP'=>'776','TRY'=>'949','TTD'=>'780','TWD'=>'901','TZS'=>'834','UAH'=>'980','UGX'=>'800','USD'=>'840','USS'=>'998','UYU'=>'858','UZS'=>'860','VEB'=>'862','VND'=>'704','VUV'=>'548','XAF'=>'950','XAG'=>'961','XAU'=>'959','XBA'=>'955','XBB'=>'956','XBC'=>'957','XBD'=>'958','XCD'=>'951','XDR'=>'960','XOF'=>'952','XPD'=>'964','XPF'=>'953','XPT'=>'962','XTS'=>'963','XXX'=>'999','YER'=>'886','ZAR'=>'710','ZMK'=>'894','ZWD'=>'716');
	
	public function selfURL() {
					$s = empty($_SERVER["HTTPS"]) ? ''
					: ($_SERVER["HTTPS"] == "on") ? "s"
					: "";
					$protocol = $this->strleft(strtolower($_SERVER["SERVER_PROTOCOL"]), "/").$s;
					$port = ($_SERVER["SERVER_PORT"] == "80") ? ""
					: (":".$_SERVER["SERVER_PORT"]);
					return $protocol."://".$_SERVER['SERVER_NAME'].$port.dirname($_SERVER['REQUEST_URI'])."/";
				}
	public function strleft($s1, $s2) {
					return substr($s1, 0, strpos($s1, $s2));
				}

	public function getDibsUrl()
	{
			return 'https://payment.architrade.com/paymentweb/start.action';
	}

	public function install()
	{
		if (!parent::install() OR !Configuration::updateValue('DIBS_MERCHANT', 'prestaworks.se')
			OR !Configuration::updateValue('DIBS_TEST', 1) OR !Configuration::updateValue('DIBS_KEY1', 'MD5 KEY 1') OR !Configuration::updateValue('DIBS_KEY2', 'MD5 KEY 2') OR !Configuration::updateValue('DIBS_CURRENCY', 1) OR !$this->registerHook('payment') OR !$this->registerHook('paymentReturn'))
			return false;
		return true;
	}

	public function uninstall()
	{
		if (!Configuration::deleteByName('DIBS_MERCHANT') OR !Configuration::deleteByName('DIBS_TEST')
		OR !Configuration::deleteByName('DIBS_KEY1') OR !Configuration::deleteByName('DIBS_KEY2')
		OR !Configuration::deleteByName('DIBS_CURRENCY')
			OR !parent::uninstall())
			return false;
		return true;
	}

	public function getContent()
	{
		$this->_html = '<h2>Dibs</h2>';
		if (isset($_POST['submitDibs']))
		{
			if (empty($_POST['merchant']))
				$this->_postErrors[] = $this->l('Merchant value is required.');
			if (empty($_POST['key1']))
				$this->_postErrors[] = $this->l('MD5 KEY 1 is required.');
			if (empty($_POST['key2']))
				$this->_postErrors[] = $this->l('MD5 KEY 2 is required.');
			if (!isset($_POST['dibstest']))
				$_POST['dibstest'] = 1;
			if (!isset($_POST['currency']))
				$_POST['currency'] = 'customer';
			if (!sizeof($this->_postErrors))
			{
				Configuration::updateValue('DIBS_MERCHANT', $_POST['merchant']);
				Configuration::updateValue('DIBS_KEY1', $_POST['key1']);
				Configuration::updateValue('DIBS_KEY2', $_POST['key2']);
				Configuration::updateValue('DIBS_TEST', intval($_POST['dibstest']));
				Configuration::updateValue('DIBS_CURRENCY', $_POST['currency']);
				$this->displayConf();
			}
			else
				$this->displayErrors();
		}

		$this->displayDibs();
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
	
	
	public function displayDibs()
	{
		$this->_html .= '
		<img src="../modules/dibs/dibs.jpg" style="float:left; margin-right:15px;" />
		<b>'.$this->l('This module allows you to accept payments by Dibs.').'</b><br /><br />
		'.$this->l('If the client chooses this payment mode, your Dibs account will be automatically credited.').'<br />
		'.$this->l('You need to configure your Dibs account first before using this module.').'
		<br /><br /><br />';
	}

	public function displayFormSettings()
	{
		$conf = Configuration::getMultiple(array('DIBS_MERCHANT', 'DIBS_TEST','DIBS_KEY1','DIBS_KEY2','DIBS_CURRENCY'));
		$merchant = array_key_exists('merchant', $_POST) ? $_POST['merchant'] : (array_key_exists('DIBS_MERCHANT', $conf) ? $conf['DIBS_MERCHANT'] : '');
		$currency = array_key_exists('currency', $_POST) ? $_POST['currency'] : (array_key_exists('DIBS_CURRENCY', $conf) ? $conf['DIBS_CURRENCY'] : '');
		$dibstest = array_key_exists('dibstest', $_POST) ? $_POST['dibstest'] : (array_key_exists('DIBS_TEST', $conf) ? $conf['DIBS_TEST'] : '');
		
		$key1 = array_key_exists('key1', $_POST) ? $_POST['key1'] : (array_key_exists('DIBS_KEY1', $conf) ? $conf['DIBS_KEY1'] : '');
		$key2 = array_key_exists('key2', $_POST) ? $_POST['key2'] : (array_key_exists('DIBS_KEY2', $conf) ? $conf['DIBS_KEY2'] : '');
		
		$this->_html .= '
		<form action="'.$_SERVER['REQUEST_URI'].'" method="post">
		<fieldset>
			<legend><img src="../img/admin/contact.gif" />'.$this->l('Settings').'</legend>
			<label>'.$this->l('Dibs Merchant').'</label>
			<div class="margin-form"><input type="text" size="33" name="merchant" value="'.htmlentities($merchant, ENT_COMPAT, 'UTF-8').'" /></div>
			<label>'.$this->l('Dibs Key1').'</label>
			<div class="margin-form"><input type="text" size="33" name="key1" value="'.htmlentities($key1, ENT_COMPAT, 'UTF-8').'" /></div>
			<label>'.$this->l('Dibs Key2').'</label>
			<div class="margin-form"><input type="text" size="33" name="key2" value="'.htmlentities($key2, ENT_COMPAT, 'UTF-8').'" /></div>
			<label>'.$this->l('Dibs testmode').'</label>
			<div class="margin-form">
				<input type="radio" name="dibstest" value="1" '.($dibstest ? 'checked="checked"' : '').' /> '.$this->l('Yes').'
				<input type="radio" name="dibstest" value="0" '.(!$dibstest ? 'checked="checked"' : '').' /> '.$this->l('No').'
			</div>
			<label>'.$this->l('Currency').'</label>
			<div class="margin-form">
				<input type="radio" name="currency" value="prestashop" '.($currency == 'prestashop' ? 'checked="checked"' : '').' /> '.$this->l('Use PrestaShop currency').'
				<br /><input type="radio" name="currency" value="customer" '.($currency == 'customer' ? 'checked="checked"' : '').' /> '.$this->l('Use customer currency').'
			</div>
			<br /><center><input type="submit" name="submitDibs" value="'.$this->l('Update settings').'" class="button" /></center>
		</fieldset>
		</form><br /><br />
		<fieldset class="width3">
			<legend><img src="../img/admin/warning.gif" />'.$this->l('Information').'</legend>
			'.$this->l('In order to use your Dibs payment module, you have to configure your Dibs account.').'<br /><br />
		</fieldset>';
	}

	public function hookPayment($params)
	{
		global $smarty;
		global $cookie;
		global $cart;
		$address = new Address(intval($params['cart']->id_address_invoice));
		$customer = new Customer(intval($params['cart']->id_customer));
		$merchant = Configuration::get('DIBS_MERCHANT');
		$dibstest = Configuration::get('DIBS_TEST');
		$id_currency = intval($params['cart']->id_currency);

		$currency = new Currency(intval($id_currency));
		if ($currency->iso_code != "SEK")
			return;

		$amount		= intval(round($cart->getOrderTotalLC(), 2) * 100);
		$ordertotal = $amount;
		//number_format(Tools::convertPrice($params['cart']->getOrderTotal(true, 3), $currency), 2, '.', '');
		//$ordertotal = str_replace(',','',$ordertotal);
		//$ordertotal = str_replace('.','',$ordertotal);		

		$currencyString = '752'; //$this->currency_iso4217code[$currency->iso_code];
		if (!Validate::isLoadedObject($address) OR !Validate::isLoadedObject($customer) OR !Validate::isLoadedObject($currency))
			return $this->l('Dibs error: (invalid address or customer)');
		$products = $params['cart']->getProducts();
		foreach ($products as $key => $product)
		{
			$products[$key]['name'] = str_replace('"', '\'', $product['name']);
			if (isset($product['attributes']))
				$products[$key]['attributes'] = str_replace('"', '\'', $product['attributes']);
			$products[$key]['name'] = htmlentities(utf8_decode($product['name']));
			$products[$key]['ProductAmount'] = number_format(Tools::convertPrice($product['price_wt'], $currency), 2, '.', '');
		}
		
		//GET CARRIER
		$carrierData = new Carrier(intval($params['cart']->id_carrier), intval($params['cart']->id_lang));
		$carriername = ($carrierData->name == '0' ? Configuration::get('PS_SHOP_NAME'): $carrierData->name);
		
		//SET WINDOW LANGUAGE
		/*
		da = Danish (default)
		sv = Swedish
		no = Norwegian
		en = English
		nl = Dutch
		de = German
		fr = French
		fi = Finnish
		es = Spanish
		it = Italian
		fo = Faroese
		pl = Polish
		*/
		$windowlang = 'en';
		if(isset($cookie->id_lang)){
			$languageIso = Language::getIsoById($cookie->id_lang);
			if($languageIso=='se'){$windowlang = 'sv';}
			if($languageIso=='dk'){$windowlang = 'da';}
			if($languageIso=='no'){$windowlang = 'no';}
			if($languageIso=='fi'){$windowlang = 'fi';}
		}
		
		$md5key=md5(Configuration::get('DIBS_KEY2').md5(Configuration::get('DIBS_KEY1').'transact='.$params['cart']->id.'&amount='.$amount.'&currency='.$dibs_cur));
		
		$md5key=md5(Configuration::get('DIBS_KEY2').md5(Configuration::get('DIBS_KEY1').'merchant='.Configuration::get('DIBS_MERCHANT').'&orderid='.$params['cart']->id.'&currency='.$currencyString.'&amount='.$ordertotal));
		
		$smarty->assign(array(
		'currencyString' => $currencyString,
		'md5key' => $md5key,
			'merchant' => $merchant,
			'address' => $address,
			'dibstest' => $dibstest,
			//'accepturl' => 'http://'.htmlspecialchars($_SERVER['HTTP_HOST'], ENT_COMPAT, 'UTF-8').__PS_BASE_URI__.'order-confirmation.php?key='.$customer->secure_key.'&id_cart='.$params['cart']->id.'&id_module='.$this->id,
			'accepturl' => 'http://'.htmlspecialchars($_SERVER['HTTP_HOST'], ENT_COMPAT, 'UTF-8').__PS_BASE_URI__.'modules/dibs/confirm.php?key='.$customer->secure_key.'&id_cart='.$params['cart']->id.'&id_module='.$this->id,
			'callbackurl' => 'http://'.htmlspecialchars($_SERVER['HTTP_HOST'], ENT_COMPAT, 'UTF-8').__PS_BASE_URI__.'modules/dibs/validation.php',
			'ordertotal' => $ordertotal,
			'shipping' =>  0,//number_format(Tools::convertPrice($params['cart']->getOrderShippingCost(), $currency), 2, '.', ''),
			'shippingMethod' => $carriername,
			'windowlanguage' => $windowlang,
			'dibsUrl' => $this->getDibsUrl(),
			
			'country' => new Country(intval($address->id_country)),
			'customer' => $customer,
						
			'products' => $products,
			'id_cart' => intval($params['cart']->id),
			
			'this_path' => $this->_path
		));

		return $this->display(__FILE__, 'dibs.tpl');
    }
	
	public function getL($key)
	{
		$translations = array(
			'transact' => $this->l('Dibs transact not set.'),
			'amount' => $this->l('Amount not set'),
			'authkey' => $this->l('authkey not set'),
			'currency' => $this->l('currency not set'),
			'orderid' => $this->l('Order ID not set'),
			'carterror' => $this->l('cart not loaded'),
			'transaction' => $this->l('TransaktionsID:'),
			'verified'=> $this->l('Not Verified')
			
		);
		return $translations[$key];
	}
	
	function hookPaymentReturn($params)
	{
		return $this->display(__FILE__, 'confirmation.tpl');
	}
}

?>
