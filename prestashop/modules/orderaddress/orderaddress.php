<?php

class OrderAddress extends OrderPage
{
	function __construct()
	{
		$this->name = 'orderaddress';
		$this->tab = 'Order';
		$this->version = '0.9';

		parent::__construct();

		$this->orderPageTitle = $this->l("Address");
		$this->displayName = $this->l('Address chooser');
		$this->description = $this->l('Lets the user choose a local address / repair/guarantee provider for each product');
	}

	function install()
	{
			if (Hook::get('orderAddressVerification') == false) {
				$hook = new Hook();
				$hook->name = 'orderAddressVerification';
				$hook->title = 'Order address verificatio';
				$hook->description = 'Allows modules to verify delivery and billing address in an order, e.g. require them to be in the same country';
				$hook->add();
			}
			if
			(
				parent::install() == false
			)
			return false;
		return true;
	}

        function validateOrderStep ($params) {	
		global $cart, $errors;

		if (!$cart->id_address_delivery OR !$cart->id_address_invoice)
			return false;

		$delivery = new Address(intval($cart->id_address_delivery));
		$invoice = new Address(intval($cart->id_address_invoice));
		if ($delivery->deleted OR $invoice->deleted)
		{
			if ($delivery->deleted)
				unset($cart->id_address_delivery);
			if ($invoice->deleted)
				unset($cart->id_address_invoice);
			return false;
		}

		return true;
        }

	function processOrderStep($params)
	{
		global $cart, $smarty, $errors;
		
		if (!isset($_POST['id_address_delivery']) OR !Address::isCountryActiveById(intval($_POST['id_address_delivery'])))
			$errors[] = 'this address is not in a valid area';
		else
		{
			$cart->id_address_delivery = intval($_POST['id_address_delivery']);
			$cart->id_address_invoice = isset($_POST['same']) ? intval($_POST['id_address_delivery']) : intval($_POST['id_address_invoice']);
			if (!$cart->update())
				$errors[] = Tools::displayError('an error occured while updating your cart');

			Module::hookExec('orderAddressVerification', array());

			if (isset($_POST['message']) AND !empty($_POST['message']))
			{
				if (!Validate::isMessage($_POST['message']))
					$errors[] = Tools::displayError('invalid message');
				elseif ($oldMessage = Message::getMessageByCartId(intval($cart->id)))
				{
					$message = new Message(intval($oldMessage['id_message']));
					$message->message = htmlentities($_POST['message'], ENT_COMPAT, 'UTF-8');
					$message->update();
				}
				else
				{
					$message = new Message();
					$message->message = htmlentities($_POST['message'], ENT_COMPAT, 'UTF-8');
					$message->id_cart = intval($cart->id);
					$message->id_customer = intval($cart->id_customer);
					$message->add();
				}
			}
		}
	}

	function displayOrderStep($params)
	{
		global $smarty, $cookie, $cart, $order_pages_hook_position, $order_pages_hook_titles;

		if (!Customer::getAddressesTotalById(intval($cookie->id_customer)))
			Tools::redirect('address.php?back=order.php?step=1');
		$customer = new Customer(intval($cookie->id_customer));
		if (Validate::isLoadedObject($customer))
		{
			/* Getting customer addresses */
			$customerAddresses = $customer->getAddresses(intval($cookie->id_lang));
			$smarty->assign('addresses', $customerAddresses);

			/* Setting default addresses for cart */
			if ((!isset($cart->id_address_delivery) OR empty($cart->id_address_delivery)) AND sizeof($customerAddresses))
			{
				$cart->id_address_delivery = intval($customerAddresses[0]['id_address']);
				$update = 1;
			}
			if ((!isset($cart->id_address_invoice) OR empty($cart->id_address_invoice)) AND sizeof($customerAddresses))
			{
				$cart->id_address_invoice = intval($customerAddresses[0]['id_address']);
				$update = 1;
			}
			/* Update cart addresses only if needed */
			if (isset($update) AND $update)
				$cart->update();

			/* If delivery address is valid in cart, assign it to Smarty */
			if (isset($cart->id_address_delivery))
			{
				$deliveryAddress = new Address(intval($cart->id_address_delivery));
				if (Validate::isLoadedObject($deliveryAddress) AND ($deliveryAddress->id_customer == $customer->id))
					$smarty->assign('delivery', $deliveryAddress);
			}

			/* If invoice address is valid in cart, assign it to Smarty */
			if (isset($cart->id_address_invoice))
			{
				$invoiceAddress = new Address(intval($cart->id_address_invoice));
				if (Validate::isLoadedObject($invoiceAddress) AND ($invoiceAddress->id_customer == $customer->id))
					$smarty->assign('invoice', $invoiceAddress);
			}
		}
		if ($oldMessage = Message::getMessageByCartId(intval($cart->id)))
			$smarty->assign('oldMessage', $oldMessage['message']);
		$smarty->assign('cart', $cart);
		$smarty->assign('back', strval(Tools::getValue('back')));

		Tools::safePostVars();
		include_once(dirname(__FILE__).'/../../header.php');
		echo $this->display(__FILE__, 'orderaddress.tpl');
	}
}


?>
