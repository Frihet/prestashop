<?php

include_once(dirname(__FILE__).'/../config/config.inc.php');

/* Getting cookie or logout */
if (!class_exists('Cookie'))
	exit();


$cookie = new Cookie('psAdmin', substr($_SERVER['SCRIPT_NAME'], strlen(__PS_BASE_URI__), -10));
if (!$cookie->isLoggedBack())
	die;

$query = Tools::getValue('q', false);

if (!$query OR $query == '' OR strlen($query) < 1)
	die();

$items = Db::s('
	SELECT v.`id_vendor`, v.`title`
	FROM `'._DB_PREFIX_.'vendor` v
	WHERE
	v.title LIKE \'%'.pSQL($query).'%\'
	ORDER BY
	v.date_upd DESC
');
if ($items)
	foreach ($items as $item)
		echo $item['title'].'|'.$item['id_vendor']."\n";
