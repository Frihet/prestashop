<?php

class OrderLogin extends OrderPage
{
	function __construct()
	{
		$this->name = 'orderlogin';
		$this->tab = 'Order';
		$this->version = '0.9';

		parent::__construct();

		$this->orderPageTitle = $this->l("Login");
		$this->displayName = $this->l('Log in / account creation');
		$this->description = $this->l('Requires the user to log in or register a new account before proceeding');
	}

        function validateOrderStep ($params) {	
		global $cookie;
		return $cookie->isLogged();
        }


	/* Display log in */
	function displayOrderStep($params)
	{
		global $cookie;
		if (!$cookie->isLogged()) {
			Tools::redirect('authentication.php?back=order.php?step=' . ($params['step'] + 1));
                } else {
			if (preg_match("/^.*step=([^&]*).*/", $_SERVER['HTTP_REFERER'], $matches)) {
                        	if (intval($matches[1]) > $params['step']) {
					Tools::redirect('order.php?step=' . ($params['step'] - 1));
				}
			}
			Tools::redirect('order.php?step=' . ($params['step'] + 1));
		}
	}
}


?>
