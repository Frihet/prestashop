CREATE TABLE `PREFIX_order_shipment` (
  `id_order_shipment` int(10) unsigned NOT NULL auto_increment,
  `id_employee` int(10) unsigned NOT NULL,
  `id_order` int(10) unsigned NOT NULL,
  `tracking_number` varchar(255),
  `date_add` datetime NOT NULL,
  PRIMARY KEY  (`id_order_shipment`),
  FOREIGN KEY (`id_employee`) REFERENCES `PREFIX_employee`(`id_employee`),
  FOREIGN KEY (`id_order`) REFERENCES `PREFIX_order`(`id_order`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
