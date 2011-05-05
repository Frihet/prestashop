<?php
include(dirname(__FILE__).'/debug.php');
include(dirname(__FILE__).'/config/config.inc.php');
include(dirname(__FILE__).'/init.php');

$js_files = array(
	'http://maps.google.com/maps/api/js?libraries=geometry&sensor=false',
	'http://code.google.com/apis/gears/gears_init.js'
);

//smartyTranslate();

$areas = array(
	'norway' => array(
			'North' => '68.35,16.67@5',
			'Mid'   => '64.49,12.06@6',
			'West'  => '62.35,6.50@7',
			'South' => '59.36,7.53@7'
		),
	'sweden' => array(
			'North' => '66.44,18.91@6',
			'Mid'   => '62.68,15.62@7',
			'South' => '58.26,14.63@7'
		),
	'denmark' => array(
			'Jylland'  => '56.42,9.31@8',
			'Sjælland' => '55.48,11.53@8'
		),
	'iceland'       => '64.10,-21.78@7',
	'faroe islands' => '62.155,-6.935@9',
	'greenland'     => '60.21,-45.25@8'
);


$smarty->assign('areas', $areas);

include(dirname(__FILE__).'/header.php');

$smarty->assign('id_lang', intval($cookie->id_lang));

$smarty->display(_PS_THEME_DIR_.'dealers-map.tpl');

include(dirname(__FILE__).'/footer.php');
/*
$db = Db::getInstance();
$query = "
SELECT
vendor.id_vendor,
product.id_product,
vendor.title,
product.location_latlng
FROM
product
LEFT JOIN vendor ON product.id_vendor = vendor.id_vendor
LEFT JOIN customer ON vendor.id_customer = customer.id_customer
WHERE
vendor.id_vendor IS NOT NULL
";
$rows = $db->ExecuteS($query);
$x = 0;
foreach($rows as $row){
	$query = "UPDATE `vendor` SET `latlng` = '" . $row['location_latlng'] . "' WHERE id_vendor = '" . $row['id_vendor'] . "'";
	echo $query . "\n";
	echo $db->Execute($query);
	echo "\n";
	$x++;
}
//echo "$x\n";
 * 
 */


