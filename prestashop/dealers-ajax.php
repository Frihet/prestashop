<?php
include(dirname(__FILE__).'/debug.php');
include(dirname(__FILE__).'/config/config.inc.php');
include(dirname(__FILE__).'/init.php');

$id_category = intval($category->id_category);

//echo $id_category;
//$id_category = 2;

$id_lang = intval($cookie->id_lang);
$dealers = ProductDealer::getDealers($id_lang, $id_category);
$dealersInfo = array();
foreach($dealers as $dealer) {
	$dealer['id_category'] = array_shift( explode(",", $dealer['id_categorytree']) );
	
	$dealerObj = new ProductDealer($dealer['id_product'], $id_lang);
	
	$vendor = $dealerObj->findVendordata();
	if (empty($vendor)) {
		continue;
	}
	if (count($vendor) >= 1) {
		$vendor = array_shift($vendor);
	}
	if (empty($vendor['latlng'])) {
		continue;
	}
	$dealersInfo[] = array(
		'id'                => $dealerObj->id,
		//'id_category'       => $dealerObj->findCategoryId($id_category),
		'id_category'       => $dealer['id_category'],
		'id_top_category'   => $id_category,
//		'latlng'            => $dealerObj->location_latlng,
		'latlng'            => $vendor['latlng'],
		'name'              => $dealerObj->name,
		'description'       => $dealerObj->description,
		'description_short' => $dealerObj->description_short,
		'data'              => $vendor
	);
}

$response = array(
	'id_lang' => $id_lang,
	'dealers' => $dealersInfo,
	'id_category' => $id_category
);

header("Content-Type: application/json");
echo json_encode($response);
