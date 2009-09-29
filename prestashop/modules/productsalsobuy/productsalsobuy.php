<?php
/**
  * Products management ... Customers who bought this item also bought ...
  * @author tripats <www.jing-shop.com> and jhnstcks
  * @ Based on a PrestaShop Module <support@prestashop.com> 
  * @copyright PrestaShop, Jing-Shop
  * @license http://www.opensource.org/licenses/osl-3.0.php Open-source licence 3.0
  * @version 0.5
  *
  */
class productsAlsobuy extends Module
{
 	function __construct()
 	{
 	 	$this->name = 'productsalsobuy';
 	 	$this->version = '0.5';
 	 	$this->tab = 'Products';
		
		parent::__construct();
		$this->page = basename(__FILE__, '.php');
		$this->displayName = $this->l('Products bought by other people');
		$this->description = $this->l('Customers who bought this item also bought ... brought to you by www.jing-shop.com ... if you like it, please consider a backlink to my shop. Thank you ');
 	}

	function install()
	{
	 	if (!parent::install())
	 		return false;
	 	return $this->registerHook('productfooter');
	}

	public function getContent()
	{
		global $cookie, $currentIndex;
		
		if (Tools::isSubmit('submitPAB'))
		{
			Configuration::updateValue('PAB_MODE', intval(Tools::getValue('pab_mode')));
			Configuration::updateValue('PAB_PIC_QUANT', intval(Tools::getValue('pab_pic_quant')));
			Tools::redirectAdmin($currentIndex.'&configure=productsalsobuy&token='.Tools::getValue('token').'&conf=4');
		}
		
		$this->_html = '
		<fieldset class="width3"><legend><img src="../modules/'.$this->name.'/logo.gif" /> '.$this->displayName.'</legend>
			<form action="'.$_SERVER['REQUEST_URI'].'" method="post">
				<label>'.$this->l('Random Mode').'</label>
				<div class="margin-form">
					<img src="../img/admin/enabled.gif" /> <input type="radio" name="pab_mode" value="1"'.(Configuration::get('PAB_MODE') ? ' checked="checked"' : '').' />
				</div>
					<label>'.$this->l('Order by quantity').'</label>
				<div class="margin-form">
			
					<img src="../img/admin/enabled.gif" /> <input type="radio" name="pab_mode" value="0"'.(!Configuration::get('PAB_MODE') ? ' checked="checked"' : '').' />
				
				</div>
								<label>'.$this->l('Number of products to display').'</label>
				<div class="margin-form">
					<input type="text" size="2" name="pab_pic_quant" value="'.Configuration::get('PAB_PIC_QUANT').'" onKeyUp="javascript:this.value = this.value.replace(/,/g, \'.\'); " />
					<p style="clear: both;">'.$this->l('Give a valid number of products').'</p>
				</div>
				<div class="clear center">
					<input type="submit" value="'.$this->l('   Save   ').'" name="submitPAB" class="button" />
				</div>
				<div class="small"><sup>*</sup> '.$this->l('Required field').'</div>
			</form>
		</fieldset><br />
		<fieldset class="width3"><legend><img src="../modules/'.$this->name.'/comment.gif" /> '.$this->l('Guide').'</legend>
			<h2>'.$this->l('Customer who bought this item also bought ... ').'</h2>
			<h3>'.$this->l('What does it do?').'</h3>
			<p>
				'.$this->l('Displays products that other people bought in the product footer').' <br />
			
			</p>
		</fieldset>';
		return $this->_html;
	}
	
	
	
	
	private function getCurrentProduct($products, $id_current)
	{
		if ($products)
			foreach ($products as $key => $product)
				if ($product['id_product'] == $id_current)
					return $key;
		return false;
	}

    private function getRelatedProducts($id_lang, $idProduct)
	{
		global $link, $cookie;
		if (Configuration::get('PAB_MODE') == 1)
		{
		$result = Db::getInstance()->ExecuteS('
		SELECT DISTINCT '._DB_PREFIX_.'order_detail.product_id AS OID, '._DB_PREFIX_.'order_detail.product_name, '._DB_PREFIX_.'product.reference,
		'._DB_PREFIX_.'product_lang.link_rewrite, '._DB_PREFIX_.'image.id_image, '._DB_PREFIX_.'product.id_product,'._DB_PREFIX_.'product_lang.name, '._DB_PREFIX_.'product.price
		FROM '._DB_PREFIX_.'order_detail
		LEFT JOIN '._DB_PREFIX_.'product ON ('._DB_PREFIX_.'product.id_product = '._DB_PREFIX_.'order_detail.product_id)
		LEFT JOIN '._DB_PREFIX_.'product_lang  ON ('._DB_PREFIX_.'product.id_product = '._DB_PREFIX_.'product_lang.id_product AND '._DB_PREFIX_.'product_lang.id_lang = '.$id_lang.')
		LEFT JOIN '._DB_PREFIX_.'image ON ('._DB_PREFIX_.'product.id_product = '._DB_PREFIX_.'image.id_product AND '._DB_PREFIX_.'image.cover = 1)
		WHERE '._DB_PREFIX_.'order_detail.id_order 
		IN(SELECT '._DB_PREFIX_.'order_detail.id_order AS TAB1 FROM '._DB_PREFIX_.'order_detail
		WHERE '._DB_PREFIX_.'order_detail.product_id = '.$idProduct.' )
		AND '._DB_PREFIX_.'product.active = 1
		AND '._DB_PREFIX_.'product.id_product != '.$idProduct.'
		GROUP BY '._DB_PREFIX_.'product.id_product
		ORDER BY RAND() LIMIT '.Configuration::get('PAB_PIC_QUANT').'
		');
		}
		
		if (Configuration::get('PAB_MODE') == 0)
		{
		$result = Db::getInstance()->ExecuteS('
		SELECT DISTINCT '._DB_PREFIX_.'order_detail.product_id AS OID, '._DB_PREFIX_.'order_detail.product_name, '._DB_PREFIX_.'product.reference,
		'._DB_PREFIX_.'product_lang.link_rewrite, '._DB_PREFIX_.'image.id_image, '._DB_PREFIX_.'product.id_product,'._DB_PREFIX_.'product_lang.name, '._DB_PREFIX_.'product.price, COUNT(*) AS quantity
		FROM '._DB_PREFIX_.'order_detail
		LEFT JOIN '._DB_PREFIX_.'product ON ('._DB_PREFIX_.'product.id_product = '._DB_PREFIX_.'order_detail.product_id)
		LEFT JOIN '._DB_PREFIX_.'product_lang ON ('._DB_PREFIX_.'product.id_product = '._DB_PREFIX_.'product_lang.id_product AND '._DB_PREFIX_.'product_lang.id_lang = '.$id_lang.')
		LEFT JOIN '._DB_PREFIX_.'image ON ('._DB_PREFIX_.'product.id_product = '._DB_PREFIX_.'image.id_product AND '._DB_PREFIX_.'image.cover = 1)
		WHERE '._DB_PREFIX_.'order_detail.id_order 
		IN(SELECT '._DB_PREFIX_.'order_detail.id_order AS TAB1 FROM '._DB_PREFIX_.'order_detail
		WHERE '._DB_PREFIX_.'order_detail.product_id = '.$idProduct.' )
		AND '._DB_PREFIX_.'product.active = 1
		AND '._DB_PREFIX_.'product.id_product != '.$idProduct.'
		GROUP BY '._DB_PREFIX_.'product.id_product
		ORDER BY quantity DESC LIMIT '.Configuration::get('PAB_PIC_QUANT').'
		');
		}
		
		
		return $result;
		
				
	}
	
   private function getRandomProducts($id_lang)
	{
		global $link, $cookie;
		$result = Db::getInstance()->ExecuteS('
		SELECT * FROM '._DB_PREFIX_.'product
		LEFT JOIN '._DB_PREFIX_.'product_lang  ON ('._DB_PREFIX_.'product_lang.id_product = '._DB_PREFIX_.'product.id_product  AND '._DB_PREFIX_.'product_lang.id_lang = '.$id_lang.')
		LEFT JOIN '._DB_PREFIX_.'image ON ('._DB_PREFIX_.'image.id_product = '._DB_PREFIX_.'product.id_product AND '._DB_PREFIX_.'image.cover = 1)	
		ORDER BY RAND() LIMIT '.Configuration::get('PAB_PIC_QUANT').'
		');
		
		return $result;
		
				
	}
	
	

	
	public function hookProductFooter($params)
	{
		global $smarty, $cookie;
		
		$idProduct = intval(Tools::getValue('id_product'));
		$id_lang = intval($cookie->id_lang);
		$relatedProducts = $this->getRelatedProducts($id_lang, $idProduct);
	    if(sizeof($relatedProducts)>0)
		{
		$rel_products = array();
		if ($relatedProducts)
			foreach ($relatedProducts AS $relProduct)
				$rel_products[] = $relProduct;
			$report = "error";
			$smarty->assign('title', 'Customers who bought this item also bought:');

			$smarty->assign('product', $rel_products);
			return $this->display(__FILE__, 'productsalsobuy.tpl');
		}
		
		if(sizeof($relatedProducts)==0)
		{
		$relatedProducts = $this->getRandomProducts($id_lang);
		$rel_products = array();
		
		
		if ($relatedProducts)
			foreach ($relatedProducts AS $relProduct)
				$rel_products[] = $relProduct;
			$report = "error";
			$smarty->assign('title', 'Customers who bought this item also bought:');

			$smarty->assign('product', $rel_products);
			return $this->display(__FILE__, 'productsalsobuy.tpl');
		
		}
		
		
	}
}
?>
