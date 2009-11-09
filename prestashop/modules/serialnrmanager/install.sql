CREATE TABLE IF NOT EXISTS `PREFIX_product_instance` (
  `id_product_instance` int(10) unsigned NOT NULL auto_increment,
  `serial` varchar(20) UNIQUE NOT NULL,
  `id_current_owner` int(10) unsigned,
  `id_order_detail` int(10) unsigned,
  `date_add` datetime NOT NULL,
  `date_upd` datetime NOT NULL,
  PRIMARY KEY (`id_product_instance`),
  FOREIGN KEY (`id_current_owner`) REFERENCES PREFIX_customer(`id_customer`),
  FOREIGN KEY (`id_order_detail`) REFERENCES PREFIX_order_detail(`id_order_detail`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
