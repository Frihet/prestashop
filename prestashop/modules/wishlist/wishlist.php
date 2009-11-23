<?php

class WishList extends Module
{
	function __construct()
	{
		$this->name = 'wishlist';
		$this->tab = 'Blocks';
		$this->version = '1.2';

		parent::__construct();

		$this->displayName = $this->l('#WishList');
		$this->description = $this->l('Allows the customer to link to his/her current cart');
	}
	
	function install()
	{
			if
			(
				parent::install() == false
				//OR $this->registerHook('top') == false
			)
			return false;
		return true;
	}
/*
	public function hookProductActions($params)
        {
                global $smarty, $compare_cart, $cookie;

                $smarty->assign('id_product', intval(Tools::getValue('id_product')));
 		$this->smartyAssigns($smarty, $params);

		return $this->display(__FILE__, 'productactions.tpl');
        }
*/
}

?>
