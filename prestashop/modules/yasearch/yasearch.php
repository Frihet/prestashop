<?php
/* Block search module created by szazman@wp.pl*/


class Yasearch extends Module
{
	function __construct()
	{
		$this->name = 'yasearch';
		$this->tab = 'Blocks';
		$this->version = 0.1;

		parent::__construct(); /* The parent construct is required for translations */

		$this->page = basename(__FILE__, '.php');
		$this->displayName = $this->l('Yet another Advanced Search block');
		$this->description = $this->l('Adds a block with a advanced search field');
	}

	function install()
	{
		if (!parent::install()
		OR $this->registerHook('top') == false
		OR $this->registerHook('leftColumn') == false
		OR $this->registerHook('rightColumn') == false
		OR Configuration::updateValue('ADVSEARCH_ATTRIBUTES', 1) == false
			OR Configuration::updateValue('ADVSEARCH_CATEGORIES', 1) == false
			OR Configuration::updateValue('ADVSEARCH_SUPPLIERS', 1) == false
			OR Configuration::updateValue('ADVSEARCH_TAGS', 1) == false
			OR Configuration::updateValue('ADVSEARCH_MANUFACTURERS', 1) == false){
			
			return false;
		}
		return true;
		
	}
	
	public function uninstall()
	{
		
		if (!Configuration::deleteByName('ADVSEARCH_ATTRIBUTES')
				OR !Configuration::deleteByName('ADVSEARCH_CATEGORIES')
				OR !Configuration::deleteByName('ADVSEARCH_SUPPLIERS')
				OR !Configuration::deleteByName('ADVSEARCH_TAGS')
				OR !Configuration::deleteByName('ADVSEARCH_MANUFACTURERS')
				OR !parent::uninstall()){
			return false;
				}
		return true;	
			
	}
	function infoForm(){
	$atr=(Configuration::get('ADVSEARCH_ATTRIBUTES')==1)?'checked="checked"':'';
	$cat=(Configuration::get('ADVSEARCH_CATEGORIES')==1)?'checked="checked"':'';
	$sup=(Configuration::get('ADVSEARCH_SUPPLIERS')==1)?'checked="checked"':'';
	$tags=(Configuration::get('ADVSEARCH_TAGS')==1)?'checked="checked"':'';
	$manu=(Configuration::get('ADVSEARCH_MANUFACTURERS')==1)?'checked="checked"':'';
	return //MD5('to').'
	'<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="7953161">
<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypal.com/pl_PL/i/scr/pixel.gif" width="1" height="1">
</form><br/>'.

	'<fieldset><legend>'.$this->l('configuration').'</legend>
	<form method="post" action="" onsubmit="return validation();">
	<table>
	<tr>
		<td><b>'.$this->l('search by attributes').'</b></td>
		<td><input type="checkbox" name="advs_attr" value="1" id="advs_attr" '.$atr.'></td>
	</tr>
	<tr>
		<td><b>'.$this->l('search by categories').'</b></td>
		<td><input type="checkbox" name="advs_cat" id="advs_cat" value="1" '.$cat.'></td>
	</tr>
	<tr>
		<td><b>'.$this->l('search by suppliers').'</b></td>
		<td><input type="checkbox" name="advs_supp" id="advs_supp" value="1" '.$sup.'></td>
	</tr>
	<tr>
		<td><b>'.$this->l('search by tags').'</b></td>
		<td><input type="checkbox" name="advs_tags" id="advs_tags" value="1" '.$tags.'></td>
	</tr>
	<tr>
		<td><b>'.$this->l('search by manufacturer').'</b></td>
		<td><input type="checkbox" name="advs_manuf" id="advs_manuf" value="1" '.$manu.'></td>
	</tr>

<tr><td colspan="2"><input type="submit" name="submitUstawienia" value="'.$this->l('save').'"></td></tr>
</table></form></fieldset>';
		}
		
		function getContent(){
		
		$output = '<h2>'.$this->displayName.'</h2>';
		
		if(Tools::isSubmit('submitUstawienia')){
			$advs_manuf=(!Tools::getValue('advs_manuf'))?0:1;
			$advs_tags=(!Tools::getValue('advs_tags'))?0:1;
			$advs_cat=(!Tools::getValue('advs_cat'))?0:1;
			$advs_supp=(!Tools::getValue('advs_supp'))?0:1;
			$advs_attr=(!Tools::getValue('advs_attr'))?0:1;
			$output.=$this->submitInfo($advs_attr,$advs_cat,$advs_supp,$advs_tags,$advs_manuf);
		}else{
			$output.=$this->infoForm();
		}
		return $output;
	}
	
	function submitInfo($advs_attr,$advs_cat,$advs_supp,$advs_tags,$advs_manuf){
		$output='';
		
				Configuration::updateValue('ADVSEARCH_ATTRIBUTES', $advs_attr);
				Configuration::updateValue('ADVSEARCH_CATEGORIES', $advs_cat);
				Configuration::updateValue('ADVSEARCH_SUPPLIERS', $advs_supp);
				Configuration::updateValue('ADVSEARCH_TAGS', $advs_tags);
				Configuration::updateValue('ADVSEARCH_MANUFACTURERS', $advs_manuf);
								
				
				$output .= '<div class="conf confirm"><img src="../img/admin/ok.gif" alt="'.$this->l('Confirmation').'" />'.$this->l('Settings updated').'</div>';			
			
			
			
			return $output;
			
	}
	function hookLeftColumn($params)
	{
		/*global $smarty;
		$smarty->assign('ENT_QUOTES', ENT_QUOTES);
		return $this->display(__FILE__, 'blocksearch.tpl');
		*/
		$this->hookTop($params);
	}

	function hookRightColumn($params)
	{
		
		$this->hookTop($params);
	}

	function hookTop($params)
	{
		
		global $cookie;
		global $smarty;
		/* GETTING ATTRIBUTES */
		if(Configuration::get('ADVSEARCH_ATTRIBUTES')==1){
			$attributeGroups = AttributeGroup::getAttributesGroups(intval($cookie->id_lang));
			if (!sizeof($attributeGroups) || $attributeGroups === false) {
				$text='';
			}else{
				$text = '<h2>'.$this->l('ATTRIBUTES').'</h2>';//.md5('TAGS').'<br>'.md5('categories');
				foreach ($attributeGroups AS $group) {
					$attributes = AttributeGroup::getAttributes(intval($cookie->id_lang), $group['id_attribute_group']);			
					if (!sizeof($attributes) || $attributes === false) {
						continue;
					}
					$text = $text . '<div onclick="showAtrybuty('.$group['id_attribute_group'].');" class="dialogOpcjeTitle">' . $group['public_name'] . '</div>';
					$text.='<div id="opcje_'.$group['id_attribute_group'].'" style="display:none;" class="opcjeItem">';
					$text.='<table>';
					foreach ($attributes AS $attribute) {
						$text = $text . '<tr><td><input name="atrybut_opcja[]" value="' . $attribute['id_attribute'] . '" type="checkbox" class="dialoginput"></td><td>&nbsp;&nbsp;'.trim($attribute['name']).'</td></tr>';
					}
					$text.='</table></div>';		
				}
			}
		}else{
			$text='';
		}
		/* GETTING ATTRIBUTES */
		/* GETTING CATEGORIES */
		if(Configuration::get('ADVSEARCH_CATEGORIES')==1){
			$result = Category::getCategories(intval($cookie->id_lang), true,false);
			$resultParents = array();
			$resultIds = array();
			foreach ($result as $row){
				$$row['name'] = Category::hideCategoryPosition($row['name']);
				$resultParents[$row['id_parent']][] = $row;
				$resultIds[$row['id_category']] = $row;
			}
			$blockCategTree = $this->getTree($resultParents, $resultIds);
			$cats=$this->showCats($blockCategTree['children'],0);
		}else{
			$cats='';
		}
		/* GETTING CATEGORIES */
		/* GETTING SUPPLIERS */
		if(Configuration::get('ADVSEARCH_SUPPLIERS')==1){
			
			$sup_sel = Supplier::getSuppliers(false,intval($params['cookie']->id_lang),true);
			if (sizeof($sup_sel)>0){
				sort( $sup_sel );
				$sup_show=1;
			}else{ 
				$sup_show=0;
			}
			if ($sup_show==1) {
				$var=array();
				foreach( $sup_sel as $sup ){
					//$sup['nb_products']=$this->CountSupPro($sup['id_supplier']);
					$var[]=$sup;
				}
				$sup_sel=$var;
			}
			$supl_display='<h2>'.$this->l('SUPPLIERS').'</h2>';
			foreach($sup_sel as $supply){
				$supl_display.= '<div class="dialogOpcjeTitle"><input type="checkbox" value="'.$supply['id_supplier'].'" name="supply[]">&nbsp;&nbsp;'.$supply['name'].'</div>';
			}
		}else{
			$supl_display='';
		}	
		/* GETTING SUPPLIERS */
		/* GETTING MANUFACTURER */
		if(Configuration::get('ADVSEARCH_MANUFACTURERS')==1){		
			$man_sel = Manufacturer::getManufacturers(true ,intval($params['cookie']->id_lang),true);
			$man_display='<h2>'.$this->l('MANUFACTURER').'</h2>';
			foreach($man_sel as $manuf){
				if($manuf['nb_products']>0){
					$man_display.= '<div class="dialogOpcjeTitle"><input type="checkbox" value="'.$manuf['id_manufacturer'].'" name="manufacturer[]">&nbsp;&nbsp;'.$manuf['name'].'</div>';
				}
			}
		}else{
			$man_display='';
		}	
		/* GETTING MANUFACTURER */
		/* GETTING TAG CLOUDS */
		if(Configuration::get('ADVSEARCH_TAGS')==1){		
			$tag_sel=array();		
			$tag_sel = Tag::getMainTags(intval($params['cookie']->id_lang),500);
			if (sizeof($tag_sel)){
				sort( $tag_sel );
			}else{
				$tag_show=0;
			}
			$tagi='<h2>'.$this->l('TAGS').'</h2><div class="dialogOpcjeTitle">';
			for($i=0;$i<(sizeof($tag_sel));$i++){
				$tagi.= '<p id="advSearchTags" onclick="parent.location.href=\'modules/yasearch/results.php?tag='.urlencode($tag_sel[$i]['name']).'\'">&nbsp;&nbsp;'.ucfirst($tag_sel[$i]['name']).'</p>';			
			}
			$tagi.='</div>';
		}else{
			$tagi='';
		}		
		/* GETTING TAG CLOUDS */
				$smarty->assign('tagi', $tagi);		
				$smarty->assign('text', $text);
				$smarty->assign('cats', $cats);
				$smarty->assign('sup_sel', $supl_display);
				$smarty->assign('man_sel', $man_display);
				$smarty->assign('ENT_QUOTES', ENT_QUOTES);
                                $smarty->assign('base_url', _PS_BASE_URL_.__PS_BASE_URI__);
				return $this->display(__FILE__, 'yasearch-header.tpl');
				
	}
	function getTree($resultParents, $resultIds, $id_category = 1, $currentDepth = 0)
	{
		//print_r($resultParents[$id_category]);
		global $link;
		$children = array();
		if (isset($resultParents[$id_category]) AND sizeof($resultParents[$id_category]) AND (Configuration::get('BLOCK_CATEG_ACCORD_MAX_DEPTH') == 0 OR $currentDepth < Configuration::get('BLOCK_CATEG_ACCORD_MAX_DEPTH')))
			foreach ($resultParents[$id_category] as $subcat)
				$children[] = $this->getTree($resultParents, $resultIds, $subcat['id_category'], $currentDepth + 1);
		if (!isset($resultIds[$id_category]))
			return false;
		return array('id' => $id_category,'name' => Category::hideCategoryPosition($resultIds[$id_category]['name']),'parent'=>$resultIds[$id_category]['id_parent'], 
					 'children' => $children);
	}
	
	function showCats($blockCategTree,$i){
		$zmienna=($i==0)?'<h2>'.$this->l('CATEGORIES').'</h2>':'';
		$disp=($i==0)?'block':'none';
		foreach($blockCategTree as $catsy){
			$zmienna.='<div id="parent_'.$catsy['parent'].'"  style="display:'.$disp.';" class="dialogOpcjeTitle">';
			$zmienna.= '<input type="checkbox" value="'.$catsy['id'].'" name="categories[]">&nbsp;&nbsp;';
			for($z=0;$z<$i;$z++){
				$zmienna.='-';
			}
			$zmienna.='<span onclick="showChildren('.$catsy['id'].');" style="color:#000000;font-weight:bold;">'.$catsy['name'].'</span></div>';
				if(count($catsy['children'])>0){
				$zmienna.=$this->showCats($catsy['children'],($i+1));
				}		
		}
		
		return $zmienna;
	}
}
