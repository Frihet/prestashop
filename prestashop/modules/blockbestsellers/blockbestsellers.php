<?php

class BlockBestSellers extends Module
{
	private $_html = '';
	private $_postErrors = array();

	function __construct()
    {
        $this->name = 'blockbestsellers';
        $this->tab = 'Blocks';
        $this->version = '1.1';

        parent::__construct();

        $this->displayName = $this->l('Top seller block');
        $this->description = $this->l('Add a block displaying the shop\'s top sellers');
    }

    public function install()
    {
        if (!parent::install() OR
			!$this->registerHook('rightColumn') OR
			!$this->registerHook('updateOrderStatus') OR
			!ProductSale::fillProductSales())
				return false;
			return true;
    }


    function hookRightColumn($params)
    {
		global $smarty, $category_path;

		$category_path_ids = array();
		foreach ($category_path as $cat)
			$category_path_ids[] = $cat['id_category'];

		$currency = new Currency(intval($params['cookie']->id_currency));
		$bestsellers = ProductSale::getBestSalesLight(intval($params['cookie']->id_lang), 0, 25);

		$best_sellers = array();
		$nr = 0;
		foreach ($bestsellers AS $bestseller) {
			if ($nr >= 5)
			        break;
		        $display = false;
			foreach(Product::getIndexedCategories($bestseller['id_product']) as $row) {
				if (in_array($row['id_category'], $category_path_ids)) {
					$display = true;
					break;
				}
			}
			if ($display) {
			        $bestseller['price'] = Tools::displayPrice(Product::getPriceStaticLC(intval($bestseller['id_product'])), $currency, false, false);
				$best_sellers[] = $bestseller;
				$nr += 1;
			}
		}
		$smarty->assign(array(
			'best_sellers' => $best_sellers,
			'mediumSize' => Image::getSize('medium'),
			'static_token' => Tools::getToken(false)));
		return $this->display(__FILE__, 'blockbestsellers.tpl');
	}
	
	function hookLeftColumn($params)
	{
		return $this->hookRightColumn($params);
	}
}

?>
