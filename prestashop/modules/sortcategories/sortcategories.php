<?php

/**
 * Author: Sariha Chabert
 * Web: http://www.platinumrds.com
 * Email: sariha@free.fr
 * Created: 2009-10-02
 * 
 * File: sortcategories.php
 * Provides:
 *  Ability to sort categories..
 * 
 */
class SortCategories extends Module
{
	function __construct()
	{
	 	$this->name = 'sortcategories';
	 	$this->tab = 'Tools';
	 	$this->version = '0.4';

	 	parent::__construct();

	 	/* The parent construct is required for translations */
		$this->page = basename(__FILE__, '.php');
        $this->displayName = $this->l('Sort Categories ');
        $this->description = $this->l('Allows you to sort the categories manually. ');
		$this->confirmUninstall = $this->l('remove this module?');
	}
	
	function install()
	{
	 	if (!parent::install()){
 			return false;
	 	}else{
			return true;
		}
	}
	
	public function getContent()
	{
		global $cookie;
		
		$output = '<h2>'.$this->displayName.'</h2>';
				
		if (Tools::isSubmit('submitNewOrder'))
		{

				$new_order = $_POST['new_order'];
				
				
				foreach($new_order as $new_position){
					//print_r($id);
					$id_cat = $new_position['id_category'];
					$position = $new_position['position'];
					$nom = addslashes(html_entity_decode($new_position['nom']));
					
					$nouveau_nom = $position.'.'.$nom;
					
					
					$table = _DB_PREFIX_.'category_lang';
					$type = 'UPDATE';
					$values['name'] = $nouveau_nom;
					$where = ' id_category = '.$id_cat;
					$where .= ' AND id_lang = '.$cookie->id_lang;
					
					if(Db::getInstance()->autoExecute($table, $values, $type, $where)){
						$result = $this->l('Categories have been sorted successfully !');
					}else{
						$errors[] .= $this->l('An error occured while inserting the new order for : ').$nouveau_nom;
					}
			}
			
			if($result){
				echo '<div class="conf confirm">'.$result.'</div>';
			}
			
			if($errors){
				foreach ($errors AS $error){
						echo '<div class="alert error">'. $error .'</div>';
					}
			}
			
		}
		return $output.$this->displayForm();
	}
	
	function ischildren($id_parent)
	{
		global $cookie;
		
		$categories = Category::getChildren($id_parent,intval($cookie->id_lang),true);
		$nb_children = count($categories);
		
		if($nb_children>1){
			return $nb_children;
		}else{
			return false;
		}
	}
	
	function getCategories($params)
	{
		global $smarty, $cookie, $currentIndex;
		
		$base_link = $currentIndex.'&configure=sortcategories&token='.$_GET['token'];
		$modules_dir = _MODULE_DIR_;

		$parent = $_GET['id_parent'];
		if(!$parent){ $parent = 1; }
		
		if (!$result = Db::getInstance()->ExecuteS('SELECT * FROM `'._DB_PREFIX_.'category` ,`'._DB_PREFIX_.'category_lang` 
		WHERE `'._DB_PREFIX_.'category`.`id_category` = `'._DB_PREFIX_.'category_lang`.`id_category`
		AND `'._DB_PREFIX_.'category`.`active` = 1
		AND `'._DB_PREFIX_.'category_lang`.`id_lang` = '.$cookie->id_lang.'
		AND `'._DB_PREFIX_.'category`.`id_parent` = '.$parent.'
		ORDER BY `'._DB_PREFIX_.'category_lang`.`name`
		') )
		return;

//print_r($result);
$i=0;
		foreach ($result as $row)
		{	
			$nom_tmp = explode('.', $result[$i]['name'], 2);
			if($nom_tmp[1] && is_numeric($nom_tmp[0])){
				$elements[$i]['position'] = $nom_tmp[0];
				$elements[$i]['nom'] = htmlentities(utf8_decode($nom_tmp[1]));
			}else{
				$elements[$i]['position'] = '000';
				$elements[$i]['nom'] = htmlentities(utf8_decode($result[$i]['name']));
			}
			
			$elements[$i]['id_category'] = $result[$i]['id_category'];
			$elements[$i]['level_depth'] = $result[$i]['level_depth'];
			$elements[$i]['id_parent'] = $result[$i]['id_parent'];
		
			if ($this->ischildren($result[$i]['id_category'])){
				 $elements[$i]['link'] = $base_link.'&id_parent='.$result[$i]['id_category'];
			}else{
				 $elements[$i]['link'] = '';
			}
			
			$i++;
		}
		
		$smarty->assign('elements', $elements);
		$smarty->assign('base_link', $base_link);
		$smarty->assign('modules_dir', $modules_dir);
		return $this->display(__FILE__, 'sortcategories.tpl');
	}
	
	
	public function displayForm()
	{
		global $currentIndex;
		
		$base_link = $currentIndex.'&configure=sortcategories&token='.$_GET['token'];
		
		$html_output = '';
		
		$style = '<style type="text/css" media="screen">
			.sortables {
				width: 350px;
				border: 1px solid silver;
				padding: 3px 3px 3px 15px;
				margin: 2px;
				cursor:move;
			}
		</style>';
		
		$html_output .= '
		'.$style.'
		<form action="'.$_SERVER['REQUEST_URI'].'" method="post">
			<fieldset>
				<legend><img src="'.$this->_path.'logo.gif" alt="" title="" />'.$this->l('Sort Manually').'</legend>
				<div align="center"><a href="'.$base_link.'&id_parent=1" class="small">'.$this->l('Back to Home').'</a></div>
				<div class="margin-form">
					'.$this->getCategories($params).'
				</div>
				<center><input type="submit" name="submitNewOrder" value="'.$this->l('Save').'" class="button" /></center>
			</fieldset>
		</form>';
		
		$html_output .= '
			<script type="text/javascript" charset="utf-8">
			$(document).ready(function(){
			    $("#sort").sortable({
				      update : function () {
							sort_my_cat();
				      }
				});
			
			sort_my_cat();
			
				function sort_my_cat() {
				$(".sortables").each(function (i) {
					i = zeroPad(i, 3);
					var id_tmp = this.id;
					var id = id_tmp.split("_");
					
					$("#position_"+id[1]).attr("value",i);
				});
			      }
			
			
				function zeroPad(num,count)
				{
				var numZeropad = num + \'\';
				while(numZeropad.length < count) {
				numZeropad = "0" + numZeropad;
				}
				return numZeropad;
				}
			
			});
			</script>
		';
		
		return $html_output;
	}
	
}
?>