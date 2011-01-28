<?php

	include(dirname(__FILE__).'/../../config/config.inc.php');

	$id_module = $_POST['id_module'];
	$id_cart = $_POST['id_cart'];
	$key = $_POST['key'];
	Tools::redirect('order-confirmation.php?key='.$key.'&id_cart='.$id_cart.'&id_module='.$id_module);
?>