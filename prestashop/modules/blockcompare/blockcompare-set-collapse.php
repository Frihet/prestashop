<?php
include(dirname(__FILE__).'/../../config/config.inc.php');
include(dirname(__FILE__).'/../../init.php');
if ( isset($_POST['ajax_blockcompare_display']) || isset($_GET['ajax_blockcompare_display']))
{
	if (Tools::getValue('ajax_blockcompare_display') == 'collapse')
	{
		$cookie->ajax_blockcompare_display = 'collapsed';
		die ('collapse status of the blockcompare module updated in the cookie');
	}
	if (Tools::getValue('ajax_blockcompare_display') == 'expand')
	{
		$cookie->ajax_blockcompare_display = 'expanded';
		die ('expand status of the blockcompare module updated in the cookie');
	}
	die ('ERROR : bad status setted. Only collapse or expand status of the blockcompare module are available.');
}
else die('ERROR : No status setted.');
?>
