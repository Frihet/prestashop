<?php

/**
  * Link class, AdvLink.php
  * Links management
  * @category classes
  *
  * @author PrestaShop <support@prestashop.com>
  * @copyright PrestaShop
  * @license http://www.opensource.org/licenses/osl-3.0.php Open-source licence 3.0
  * @version 1.2
  *
  */

include_once(dirname(__file__)."/../../../classes/Link.php");

class AdvLink extends Link
{
	/**
	  * Create link after language change
	  *
	  * @param integer $id_lang Language ID
	  * @return string link
	  */
	public function getLanguageLink($id_lang)
	{
		if ($this->allow == 1)
			return _PS_BASE_URL_.__PS_BASE_URI__.'lang-'.Language::getIsoById($id_lang).'/'.substr(preg_replace('#/lang-([a-z]{2})/#', '/', $_SERVER['REQUEST_URI']), strlen(__PS_BASE_URI__));
		else
			return $this->getUrlWith('id_lang', intval($id_lang));
	}
	public function arrayToString($array){
	
		$url_elements[$array]='';
		if(is_array($_GET[$array])){
			if($_GET[$array][0]>0){
				$i=0;
				foreach($_GET[$array] as $key=>$val){
					$url_elements[$array].= $val.',';
				$i++;	
				}
			$url_elements[$array]=substr($url_elements[$array], 0, -1);				
			}
		
		}else{
			$pos = strpos($_GET[$array], ',');
				if ($pos === false) {
					$url_elements[$array]=urldecode($_GET[$array]);
				}else{
					$url_elements[$array] =intval($_GET[$array]);
				}
			}
			
			return urlencode($url_elements[$array]);
		
	
	}
	public function getPaginationLink($type, $id_object, $nb = FALSE, $sort = FALSE, $pagination = FALSE, $array = FALSE)
	{
		
		$url_elements=$_GET;
		if(isset($_GET['atrybut_opcja'])){
			$url_elements['atrybut_opcja']=$this->arrayToString('atrybut_opcja');	
		}
		if(isset($_GET['categories'])){
			$url_elements['categories']=$this->arrayToString('categories');
		}
		if(isset($_GET['supply'])){
			$url_elements['supply']=$this->arrayToString('supply');
		}
		if(isset($_GET['manufacturer'])){
					$url_elements['manufacturer']=$this->arrayToString('manufacturer');
		}
		
		if ($type AND $id_object)
			$url = $this->{'get'.$type.'Link'}($id_object, NULL);
		else
			$url = $this->url;
		$vars = (!$array ? '' : array());
		$varsNb = array('n', 'search_query');
		$varsSort = array('orderby', 'orderway');
		$varsPagination = array('p');
		$varsVarious = array('search_query');
		
		foreach ($url_elements as $k => $value)
			if ($k != 'id_'.$type)
			{
				$ifNb = (!$nb OR ($nb AND !in_array($k, $varsNb)));
				$ifSort = (!$sort OR ($sort AND !in_array($k, $varsSort)));
				$ifPagination = (!$pagination OR ($pagination AND !in_array($k, $varsPagination)));
				if ($ifNb AND $ifSort AND $ifPagination AND !is_array($value))
					!$array ? ($vars .= ((!$n++ AND ($this->allow == 1 OR $url == $this->url)) ? '?' : '&').urlencode($k).'='.urlencode($value)) : ($vars[urlencode($k)] = urlencode($value));
			}
		if (!$array)
			return $url.$vars;
		$vars['requestUrl'] = $url;
		if ($type AND $id_object)
			$vars['id_'.$type] = (is_object($id_object) ? intval($id_object->id) : intval($id_object));
		return $vars;
	}
}

?>
