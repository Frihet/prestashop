CREATE TABLE IF NOT EXISTS `PREFIX_postcode` (
  `id_postcode` int(10) unsigned NOT NULL auto_increment,
  `id_country` int(10) unsigned NOT NULL,
  `name` varchar(11) NOT NULL,
  `longitude` float(10) unsigned NOT NULL,
  `latitude` float(10) unsigned NOT NULL,
  FOREIGN KEY (`id_country`) REFERENCES `PREFIX_country`(`id_country`),
  PRIMARY KEY (`id_postcode`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `PREFIX_vendor` (
  `id_vendor` int(10) unsigned NOT NULL auto_increment,
  `id_customer` int(10) unsigned,
  `id_postcode` int(10) NOT NULL,
  `title` varchar(256) NOT NULL,
  `distance` float(10) unsigned NOT NULL,
  `date_add` datetime NOT NULL,
  `date_upd` datetime NOT NULL,
  FOREIGN KEY (`id_postcode`) REFERENCES `PREFIX_postcode`(`id_postcode`),
  FOREIGN KEY (`id_customer`) REFERENCES `PREFIX_customer`(`id_customer`),
  PRIMARY KEY (`id_vendor`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `PREFIX_vendor_support` (
  `id_vendor` int(10) unsigned NOT NULL,
  `id_product` int(10) unsigned NOT NULL,
  FOREIGN KEY (`id_vendor`) REFERENCES `PREFIX_vendor`(`id_vendor`),
  FOREIGN KEY (`id_product`) REFERENCES `PREFIX_product`(`id_product`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
