<?php

/** MODULO CRIADO POR ODLANIER
 * @author Odlanier de Souza Mendes
 * @copyright Dlani
 * @email master_odlanier@hotmail.com
 * @email mends@prestashopbr.com
 * @version 0.3
 **/
class blockrss2 extends Module
{
	function __construct()
	{
		$this->name = 'blockrss2';
		$this->tab = 'Blocks';
		$this->version = 0.3;

		parent::__construct(); // The parent construct is required for translations

		$this->page = basename(__FILE__, '.php');
		$this->displayName = $this->l('Block RSS2.0');
		$this->description = $this->l('Adds a block to display Icons Web 2.0');
	}

	function install()
	{
		if (!parent::install())
			return false;
		if (!$this->registerHook('rightColumn') OR !$this->registerHook('leftColumn'))
			return false;
		return true;
	}

	/**
	* Returns module content
	*
	* @param array $params Parameters
	* @return string Content
	*/
	function hookRightColumn($params)
	{
		global $smarty, $cookie;
		
		if (!isset($_GET['id_category']) OR !Validate::isUnsignedId($_GET['id_category']))
			$id_category = 0;
		
		else
		{
			$category = new Category(intval(Tools::getValue('id_category')), intval($cookie->id_lang));
			if (!Validate::isLoadedObject($category))
				$id_category = 0;	
			
			else
			$id_category = $category->id_category;
		}
		
			
		$smarty->assign
		( array
			(
			'id_category' => $id_category,
			'SHOPNAME' => Configuration::get('SHOP_NAME') 
			)
		);
		return $this->display(__FILE__, 'blockrss2.tpl');
	}

	function hookLeftColumn($params)
	{
		return $this->hookRightColumn($params);
	}

}

?>