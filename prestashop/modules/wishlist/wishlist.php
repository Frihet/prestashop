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
				OR $this->registerHook('shoppingCartExtra') == false
			)
			return false;
		return true;
	}
	public function hookShoppingCartExtra($params)
        {
		return $this->display(__FILE__, 'cartlink.tpl');
        }
}

?>
