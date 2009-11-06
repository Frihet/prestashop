<?php

class OrderPage extends Module
{	
	function install()
	{
		if (Hook::get('orderPages') == false) {
				$hook = new Hook();
				$hook->name = 'orderPages';
				$hook->title = 'Order process pages';
				$hook->description = 'Adds new pages in the order process';
				$hook->add();
			}
		if
			(
				parent::install() == false
				OR $this->registerHook('orderPages') == false
			)
			return false;
		return true;
	}

	public function hookOrderPages($params)
        {
		global $smarty, $cookie, $cart, $errors, $order_pages_hook_stay, $order_pages_hook_position, $order_pages_hook_titles, $order_pages_hook_names;

		if ($params['part'] == 'title') {
			$order_pages_hook_titles[] = $this->orderPageTitle;
                } else if ($params['part'] == 'name') {
			$order_pages_hook_names[] = $this->name;
                } else if ($params['part'] == 'body') {
			if ($params['step'] == $order_pages_hook_position) {
				if (Tools::isSubmit('process' . $order_pages_hook_position) OR isset($_GET['submit' . $order_pages_hook_position])) {
					$order_pages_hook_stay = false;
                                        $this->processOrderStep($params);
					if (!$order_pages_hook_stay AND !sizeof($errors)) {
						Tools::redirect('order.php?step=' . ($params['step'] + 1));
					}
				}
				if (sizeof($errors))
					$smarty->assign('errors', $errors);

				$smarty->assign('order_steps', $order_pages_hook_titles);
				$smarty->assign('order_step', $order_pages_hook_position);

				$this->displayOrderStep($params);
			} else if ($params['step'] > $order_pages_hook_position AND !$this->validateOrderStep($params)) {
					Tools::redirect('order.php?step=' . $order_pages_hook_position);
			}
			$order_pages_hook_position += 1;
                }
        }

	/*
	 * Validate that step has been preformed successfully
	 */
        function validateOrderStep ($params)
        {
		return true;
        }

	/*
	 * Manage input from page
	 */
	function processOrderStep($params)
	{
        }

	/* Display page */
	function displayOrderStep($params)
        {
        }
}
