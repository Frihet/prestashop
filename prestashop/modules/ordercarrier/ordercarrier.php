<?php

class OrderCarrier extends OrderPage
{
	function __construct()
	{
		$this->name = 'ordercarrier';
		$this->tab = 'Order';
		$this->version = '0.9';

		parent::__construct();

		$this->orderPageTitle = $this->l("Carrier");
		$this->displayName = $this->l('Carrier selection');
		$this->description = $this->l('Lets the user select carrier');
	}

	function install()
	{
		if (Hook::get('extraCarrierDetails') == false) {
				$hook = new Hook();
				$hook->name = 'extraCarrierDetails';
				$hook->title = 'Extra carrier dietails';
				$hook->description = 'Extra carrier dietails display part';
				$hook->add();
		}
		if (Hook::get('extraCarrierDetailsProcess') == false) {
				$hook = new Hook();
				$hook->name = 'extraCarrierDetailsProcess';
				$hook->title = 'Extra carrier dietails';
				$hook->description = 'Extra carrier dietails processing part';
				$hook->add();
		}
		return parent::install();
	}

	/* Validate carrier information */
	function validateOrderStep($params)
	{
		global $cart;
		return $cart->id_carrier OR $cart->isVirtualCart();
        }

	/* Process carrier information */
	function processOrderStep($params)
	{
		global $cart, $smarty, $errors, $isVirtualCart, $orderTotal;

		$cart->recyclable = (isset($_POST['recyclable']) AND !empty($_POST['recyclable'])) ? 1 : 0;

		if (isset($_POST['gift']) AND !empty($_POST['gift']))
		{
			if (!Validate::isMessage($_POST['gift_message']))
				$errors[] = Tools::displayError('invalid gift message');
			else
			{
				$cart->gift = 1;
				$cart->gift_message = strip_tags($_POST['gift_message']);
			}
		}
		else
			$cart->gift = 0;

		$address = new Address(intval($cart->id_address_delivery));
		if (!Validate::isLoadedObject($address))
			die(Tools::displayError());
		if (!$id_zone = Address::getZoneById($address->id))
			$errors[] = Tools::displayError('no zone match with your address');
		if (isset($_POST['id_carrier']) AND Validate::isInt($_POST['id_carrier']) AND sizeof(Carrier::checkCarrierZone(intval($_POST['id_carrier']), intval($id_zone))))
			$cart->id_carrier = intval($_POST['id_carrier']);
		elseif (!$isVirtualCart)
			$errors[] = Tools::displayError('invalid carrier or no carrier selected');

		Module::hookExec('extraCarrierDetailsProcess', array('carrier' => new Carrier($cart->id_carrier)));

		$cart->update();
	}

	/* Display carrier form */
	function displayOrderStep($params)
	{
		global $smarty, $cart, $cookie, $defaultCountry;

		if ($isVirtualCart = $cart->isVirtualCart()) {
			$cart->id_carrier = 0;
			$cart->update();
		}
		$smarty->assign('virtual_cart', $isVirtualCart);

		$address = new Address(intval($cart->id_address_delivery));
		$id_zone = Address::getZoneById($address->id);
		$result = Carrier::getCarriers(intval($cookie->id_lang), true, false, intval($id_zone));
		$resultsArray = array();
		foreach ($result AS $k => $row)
		{
			$carrier = new Carrier(intval($row['id_carrier']));
			if ((Configuration::get('PS_SHIPPING_METHOD') AND !$carrier->getMaxDeliveryPriceByWeight($id_zone))
			OR (!Configuration::get('PS_SHIPPING_METHOD') AND !$carrier->getMaxDeliveryPriceByPrice($id_zone)))
			{
				unset($result[$k]);
				continue ;
			}
			if ($row['range_behavior'])
			{
				// Get id zone
			if (isset($cart->id_address_delivery) AND $cart->id_address_delivery)
					$id_zone = Address::getZoneById(intval($cart->id_address_delivery));
				else
					$id_zone = intval($defaultCountry->id_zone);
				if ((Configuration::get('PS_SHIPPING_METHOD') AND (!Carrier::checkDeliveryPriceByWeight($row['id_carrier'], $cart->getTotalWeight(), $id_zone)))
				OR (!Configuration::get('PS_SHIPPING_METHOD') AND (!Carrier::checkDeliveryPriceByPrice($row['id_carrier'], $cart->getOrderTotalLC(true, 4), $id_zone))))
					{
						unset($result[$k]);
						continue ;
					}
			}
			$row['name'] = (strval($row['name']) != '0' ? $row['name'] : Configuration::get('PS_SHOP_NAME'));
			$row['price'] = $cart->getOrderShippingCostLC(intval($row['id_carrier']));
			$row['price_tax_exc'] = $cart->getOrderShippingCostLC(intval($row['id_carrier']), false);
			$row['img'] = file_exists(_PS_SHIP_IMG_DIR_.intval($row['id_carrier']).'.jpg') ? _THEME_SHIP_DIR_.intval($row['id_carrier']).'.jpg' : '';
			$row['extra'] = Module::hookExec('extraCarrierDetails', array("row" => $row, "carrier" => $carrier));
			$resultsArray[] = $row;
		}

		// Wrapping fees
		$wrapping_fees = floatval(Configuration::get('PS_GIFT_WRAPPING_PRICE'));
		$wrapping_fees_tax = new Tax(intval(Configuration::get('PS_GIFT_WRAPPING_TAX')));
		$wrapping_fees_tax_exc = $wrapping_fees / (1 + ((floatval($wrapping_fees_tax->rate) / 100)));

		if (Validate::isUnsignedInt($cart->id_carrier))
		{
			$carrier = new Carrier(intval($cart->id_carrier));
			if ($carrier->active AND !$carrier->deleted)
				$checked = intval($cart->id_carrier);
		}
		if (!isset($checked))
			$checked = intval(Configuration::get('PS_CARRIER_DEFAULT'));
		$smarty->assign(array(
			'checkedTOS' => intval($cookie->checkedTOS),
			'recyclablePackAllowed' => intval(Configuration::get('PS_RECYCLABLE_PACK')),
			'giftAllowed' => intval(Configuration::get('PS_GIFT_WRAPPING')),
			'conditions' => intval(Configuration::get('PS_CONDITIONS')),
			'recyclable' => intval($cart->recyclable),
			'gift_wrapping_price' => floatval(Configuration::get('PS_GIFT_WRAPPING_PRICE')),
			'carriers' => $resultsArray,
			'HOOK_EXTRACARRIER' => Module::hookExec('extraCarrier', array('address' => $address)),
			'checked' => intval($checked),
			'back' => strval(Tools::getValue('back')),
			'total_wrapping' => number_format($wrapping_fees, 2, '.', ''),
			'total_wrapping_tax_exc' => number_format($wrapping_fees_tax_exc, 2, '.', '')));
		Tools::safePostVars();
		$css_files = array(__PS_BASE_URI__.'css/thickbox.css' => 'all');
		$js_files = array(__PS_BASE_URI__.'js/jquery/thickbox-modified.js');
		include_once(dirname(__FILE__).'/../../header.php');
		echo $this->display(__FILE__, 'ordercarrier.tpl');
	}
}


?>
