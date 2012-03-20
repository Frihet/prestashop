<?php

class BlockCompare extends Module
{
        const INSTALL_SQL_FILE = 'install.sql';

        private $_html = '';
        private $_postErrors = array();

	function __construct()
	{
		$this->name = 'blockcompare';
		$this->tab = 'Blocks';
		$this->version = '1.2';

		parent::__construct();

		$this->displayName = $this->l('Compare block');
		$this->description = $this->l('Allows the customer to compare products by options');
	}
	
	function install()
	{
			if (!file_exists(dirname(__FILE__).'/'.self::INSTALL_SQL_FILE))
				return (false);
			else if (!$sql = file_get_contents(dirname(__FILE__).'/'.self::INSTALL_SQL_FILE))
				return (false);
			$sql = str_replace('PREFIX_', _DB_PREFIX_, $sql);
			$sql = preg_split("/;\s*[\r\n]+/",$sql);
			foreach ($sql as $query)
				if (trim($query) && !Db::getInstance()->Execute(trim($query)))
					return (false);
			if
			(
				parent::install() == false
				OR $this->registerHook('top') == false
				OR $this->registerHook('productActions') == false
				OR $this->registerHook('productListActions') == false
				OR $this->registerHook('rightColumn') == false
				OR Configuration::updateValue('PS_BLOCK_CART_AJAX', 1) == false
			)
			return false;
		return true;
	}

	public function hookProductActions($params)
        {
                global $smarty, $compare_cart, $cookie;

                $smarty->assign('id_product', intval(Tools::getValue('id_product')));
 		$this->smartyAssigns($smarty, $params);

		return $this->display(__FILE__, 'productactions.tpl');
        }

	public function hookProductListActions($params)
        {
                global $smarty, $compare_cart, $cookie;

		return $this->display(__FILE__, 'productlistactions.tpl');
        }

	function hookTop($params)
        {
                global $compare_cart, $cookie;

                require_once(dirname(__FILE__).'/CompareCart.php');

		if (is_numeric($cookie->id_compare_cart))
		{
			$compare_cart = new CompareCart(intval($cookie->id_compare_cart));
			if ($cookie->id_customer)
				$compare_cart->id_customer = intval($cookie->id_customer);
			$compare_cart->update();
		}

		if (!isset($compare_cart) OR !$compare_cart->id)
		{
			$compare_cart = new CompareCart();
			$compare_cart->id_guest = intval($cookie->id_guest);
		        if ($cookie->id_customer)
			        $compare_cart->id_customer = intval($cookie->id_customer);
		        if ($cookie->id_lang)
			        $compare_cart->id_lang = intval($cookie->id_lang);
		}
        }

	function hookRightColumn($params)
	{
		global $smarty, $compare_cart;

		$this->smartyAssigns($smarty, $params);
 		$smarty->assign(array('products' => $compare_cart->getProducts()));
		return $this->display(__FILE__, 'blockcompare.tpl');
	}

	function hookLeftColumn($params)
	{
		return $this->hookRightColumn($params);
	}

	function hookAjaxCall($params)
	{
		global $smarty;
		$this->smartyAssigns($smarty, $params);
		return $this->display(__FILE__, 'blockcompare-json.tpl');
	}
	function smartyAssigns(&$smarty, &$params)
	{
		global $errors, $compare_cart;

		$smarty->assign(array(
                        'compare_cart' => $compare_cart,
			'products'=> $compare_cart->getProducts(true),
			'ajax_allowed' => intval(Configuration::get('PS_BLOCK_CART_AJAX')) == 1 ? true : false
		));

		if (sizeof($errors))
			$smarty->assign('errors', $errors);
		if(intval(Configuration::get('PS_BLOCK_CART_AJAX')) == 1)
			$smarty->assign('colapseExpandStatus', $params['cookie']->ajax_blockcompare_display);
	}

	public function getContent()
	{
		$output = '<h2>'.$this->displayName.'</h2>';
		if (Tools::isSubmit('submitBlockCompare'))
		{
			$ajax = Tools::getValue('ajax');
			if ($ajax != 0 AND $ajax != 1)
				$output .= '<div class="alert error">'.$this->l('Ajax : Invalid choice.').'</div>';
			else
			{
				Configuration::updateValue('PS_BLOCK_CART_AJAX', intval($ajax));
			}
				$output .= '<div class="conf confirm"><img src="../img/admin/ok.gif" alt="'.$this->l('Confirmation').'">'.$this->l('Settings updated').'</div>';
		}
		return $output.$this->displayForm();
	}

	public function displayForm()
	{
		return '
		<form action="'.$_SERVER['REQUEST_URI'].'" method="post">
			<fieldset>
				<legend><img src="'.$this->_path.'logo.gif" alt="" title="">'.$this->l('Settings').'</legend>
				
				<label>'.$this->l('Ajax compare').'</label>
				<div class="margin-form">
					<input type="radio" name="ajax" id="ajax_on" value="1" '.(Tools::getValue('ajax', Configuration::get('PS_BLOCK_CART_AJAX')) ? 'checked="checked" ' : '').'>
					<label class="t" for="ajax_on"> <img src="../img/admin/enabled.gif" alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'"></label>
					<input type="radio" name="ajax" id="ajax_off" value="0" '.(!Tools::getValue('ajax', Configuration::get('PS_BLOCK_CART_AJAX')) ? 'checked="checked" ' : '').'>
					<label class="t" for="ajax_off"> <img src="../img/admin/disabled.gif" alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'"></label>
					<p class="clear">'.$this->l('Activate AJAX mode for compare (compatible with the default theme)').'</p>
				</div>
				
				<center><input type="submit" name="submitBlockCompare" value="'.$this->l('Save').'" class="button"></center>
			</fieldset>
		</form>';
	}
}

?>
