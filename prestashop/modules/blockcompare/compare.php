<?php

include(dirname(__FILE__).'/../../debug.php');
require_once(dirname(__FILE__).'/../../config/config.inc.php');
require_once(dirname(__FILE__).'/../../init.php');
require_once(dirname(__FILE__).'/blockcompare.php');

$errors = array();

$add = Tools::getIsset('add') ? 1 : 0;
$delete = Tools::getIsset('delete') ? 1 : 0;
$update = Tools::getIsset('update') ? 1 : 0;


$block = new BlockCompare();
$block->hookTop(array());

//update the compare_cart...
if ($add OR $update OR $delete)
{
	//get the values
 	$idProduct = intval(Tools::getValue('id_product', NULL));

        if (!$idProduct)
		$errors[] = Tools::displayError('product not found');
	else
	{
		$producToAdd = new Product(intval($idProduct), false, intval($cookie->id_lang));
		if ((!$producToAdd->id OR !$producToAdd->active) AND !$delete)
			$errors[] = Tools::displayError('product is no longer available');
		else
		{
			if ($add)
			{
				$compare_cart->addProduct(intval($idProduct));

				/* Product addition to the compare_cart */
				if (!isset($compare_cart->id) OR !$compare_cart->id)
				{
				    $compare_cart->add();
				    if ($compare_cart->id)
						$cookie->id_compare_cart = intval($compare_cart->id);
				}

			}
			elseif ($delete)
			{
				$compare_cart->deleteProduct(intval($idProduct));
			}
		}
		if (!sizeof($errors))
		{
			$queryString = Tools::safeOutput(Tools::getValue('query', NULL));
			if ($queryString AND !Configuration::get('PS_CART_REDIRECT'))
				Tools::redirect('search.php?search='.$queryString);
			if (isset($_SERVER['HTTP_REFERER']))
			{
				// Redirect to previous page
				preg_match('!http(s?)://(.*)/(.*)!', $_SERVER['HTTP_REFERER'], $regs);
				if (isset($regs[3]) AND !Configuration::get('PS_CART_REDIRECT') AND Tools::getValue('ajax') != 'true')
					Tools::redirect($regs[3]);
			}
		}
	}
}

//if compare.php is called by ajax
if (Tools::getValue('ajax') == 'true')
	require_once(_PS_MODULE_DIR_.'/blockcompare/blockcompare-ajax.php');
else
{
	if (sizeof($errors))
	{
		require_once(dirname(__FILE__).'/../../header.php');
		$smarty->assign('errors', $errors);
		$smarty->display(_PS_THEME_DIR_.'errors.tpl');
		require_once(dirname(__FILE__).'/../../footer.php');
	}
	else
		Tools::redirect('modules/blockcompare/comparecart.php?'.(isset($idProduct) ? 'ipa='.intval($idProduct) : ''));
}
