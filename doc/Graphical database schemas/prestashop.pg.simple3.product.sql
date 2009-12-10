SET NAMES 'utf8';
CREATE TABLE accessory (
  id_product_1 int NOT NULL,
  id_product_2 int NOT NULL
);
CREATE TABLE address (
  id_address serial NOT NULL,
  id_country int NOT NULL,
  id_state int default NULL,
  id_customer int NOT NULL default '0',
  id_manufacturer int NOT NULL default '0',
  id_supplier int NOT NULL default '0',
  PRIMARY KEY (id_address)
);
CREATE TABLE attachment (
  id_attachment serial NOT NULL,
  PRIMARY KEY (id_attachment)
);
CREATE TABLE product_attachment (
  id_product int NOT NULL,
  id_attachment int NOT NULL,
  PRIMARY KEY (id_product,id_attachment)
);
CREATE TABLE attribute (
  id_attribute serial NOT NULL,
  id_attribute_group int NOT NULL,
  PRIMARY KEY (id_attribute)
);
CREATE TABLE attribute_group (
  id_attribute_group serial NOT NULL,
  PRIMARY KEY (id_attribute_group)
);
CREATE TABLE attribute_impact (
  id_attribute_impact serial NOT NULL,
  id_product int NOT NULL,
  id_attribute int NOT NULL,
  PRIMARY KEY (id_attribute_impact)
);
CREATE TABLE category (
  id_category serial NOT NULL,
  id_parent int NOT NULL,
  PRIMARY KEY (id_category)
);
CREATE TABLE category_group (
  id_category int NOT NULL,
  id_group int NOT NULL
);
CREATE TABLE category_product (
  id_category int NOT NULL,
  id_product int NOT NULL
);
CREATE TABLE country (
  id_country serial NOT NULL,
  id_zone int NOT NULL,
  PRIMARY KEY (id_country)
);
CREATE TABLE customization (
  id_customization serial NOT NULL,
  id_product_attribute int NOT NULL default '0',
  id_cart int NOT NULL,
  id_product int NOT NULL,
  PRIMARY KEY (id_customization)
);
CREATE TABLE customization_field (
  id_customization_field serial NOT NULL,
  id_product int NOT NULL,
  PRIMARY KEY (id_customization_field)
);
CREATE TABLE customized_data (
  id_customization int NOT NULL,
  PRIMARY KEY (id_customization)
);
CREATE TABLE feature (
  id_feature serial NOT NULL,
  PRIMARY KEY (id_feature)
);
CREATE TABLE feature_product (
  id_feature int NOT NULL,
  id_product int NOT NULL,
  id_feature_value int NOT NULL,
  PRIMARY KEY (id_feature,id_product)
);
CREATE TABLE feature_value (
  id_feature_value serial NOT NULL,
  id_feature int NOT NULL,
  PRIMARY KEY (id_feature_value)
);
CREATE TABLE _group (
  id_group serial NOT NULL,
  PRIMARY KEY (id_group)
);
CREATE TABLE image (
  id_image serial NOT NULL,
  id_product int NOT NULL,
  PRIMARY KEY (id_image)
);
CREATE TABLE manufacturer (
  id_manufacturer serial NOT NULL,
  PRIMARY KEY (id_manufacturer)
);
CREATE TABLE pack (
  id_product_pack int NOT NULL,
  id_product_item int NOT NULL,
  PRIMARY KEY (id_product_pack,id_product_item)
);
CREATE TABLE product (
  id_product serial NOT NULL,
  id_supplier int default NULL,
  id_manufacturer int default NULL,
  id_tax int NOT NULL,
  id_category_default int default NULL,
  id_color_default int default NULL,
  PRIMARY KEY (id_product)
);
CREATE TABLE product_attribute (
  id_product_attribute serial NOT NULL,
  id_product int NOT NULL,
  PRIMARY KEY (id_product_attribute)
);
CREATE TABLE product_attribute_combination (
  id_attribute int NOT NULL,
  id_product_attribute int NOT NULL,
  PRIMARY KEY (id_attribute,id_product_attribute)
);
CREATE TABLE product_attribute_image (
  id_product_attribute int NOT NULL,
  id_image int NOT NULL,
  PRIMARY KEY (id_product_attribute,id_image)
);
CREATE TABLE product_download (
  id_product_download serial NOT NULL,
  id_product int NOT NULL,
  PRIMARY KEY (id_product_download)
);
CREATE TABLE product_sale (
  id_product int NOT NULL,
  PRIMARY KEY (id_product)
);
CREATE TABLE product_tag (
  id_product int NOT NULL,
  id_tag int NOT NULL,
  PRIMARY KEY (id_product,id_tag)
);
CREATE TABLE scene (
  id_scene serial NOT NULL,
  PRIMARY KEY (id_scene)
);
CREATE TABLE scene_category (
  id_scene int NOT NULL,
  id_category int NOT NULL,
  PRIMARY KEY (id_scene,id_category)
);
CREATE TABLE scene_products (
  id_scene int NOT NULL,
  id_product int NOT NULL,
  PRIMARY KEY (id_scene, id_product)
);
CREATE TABLE search_index (
  id_product int NOT NULL,
  id_word int NOT NULL,
  PRIMARY KEY (id_word, id_product)
);
CREATE TABLE search_word (
  id_word serial NOT NULL,
  id_lang int NOT NULL,
  PRIMARY KEY (id_word)
);
CREATE TABLE state (
  id_state serial NOT NULL,
  id_country int NOT NULL,
  id_zone int NOT NULL,
  PRIMARY KEY (id_state)
);
CREATE TABLE supplier (
  id_supplier serial NOT NULL,
  PRIMARY KEY (id_supplier)
);
CREATE TABLE tag (
  id_tag serial NOT NULL,
  id_lang int NOT NULL,
  PRIMARY KEY (id_tag)
);
CREATE TABLE tax (
  id_tax serial NOT NULL,
  PRIMARY KEY (id_tax)
);
CREATE TABLE tax_state (
  id_tax int NOT NULL,
  id_state int NOT NULL
);
CREATE TABLE tax_zone (
  id_tax int NOT NULL,
  id_zone int NOT NULL
);
CREATE TABLE zone (
  id_zone serial NOT NULL,
  PRIMARY KEY (id_zone)
);

----

ALTER TABLE customized_data ADD FOREIGN KEY (id_customization) REFERENCES customization (id_customization);
ALTER TABLE feature_product ADD FOREIGN KEY (id_feature_value) REFERENCES feature_value (id_feature_value);
ALTER TABLE pack ADD FOREIGN KEY (id_product_pack) REFERENCES product (id_product);
ALTER TABLE pack ADD FOREIGN KEY (id_product_item) REFERENCES product (id_product);
ALTER TABLE search_index ADD FOREIGN KEY (id_product) REFERENCES product (id_product);
ALTER TABLE search_index ADD FOREIGN KEY (id_word) REFERENCES search_word (id_word);
ALTER TABLE scene_products ADD FOREIGN KEY (id_scene) REFERENCES scene (id_scene);
ALTER TABLE scene_products ADD FOREIGN KEY (id_product) REFERENCES product (id_product);
ALTER TABLE scene_category ADD FOREIGN KEY (id_scene) REFERENCES scene (id_scene);
ALTER TABLE scene_category ADD FOREIGN KEY (id_category) REFERENCES category (id_category);
ALTER TABLE product_tag ADD FOREIGN KEY (id_product) REFERENCES product (id_product);
ALTER TABLE product_tag ADD FOREIGN KEY (id_tag) REFERENCES tag (id_tag);
ALTER TABLE product_sale ADD FOREIGN KEY (id_product) REFERENCES product (id_product);
ALTER TABLE product_download ADD FOREIGN KEY (id_product) REFERENCES product (id_product);
ALTER TABLE product_attribute_combination ADD FOREIGN KEY (id_attribute) REFERENCES attribute (id_attribute);
ALTER TABLE product_attribute_combination ADD FOREIGN KEY (id_product_attribute) REFERENCES product_attribute (id_product_attribute);
ALTER TABLE product_attachment ADD FOREIGN KEY (id_product) REFERENCES product (id_product);
ALTER TABLE product_attachment ADD FOREIGN KEY (id_attachment) REFERENCES attachment (id_attachment);
ALTER TABLE feature_product ADD FOREIGN KEY (id_feature) REFERENCES feature (id_feature);
ALTER TABLE feature_product ADD FOREIGN KEY (id_product) REFERENCES product (id_product);
ALTER TABLE customization_field ADD FOREIGN KEY (id_product) REFERENCES product (id_product);
ALTER TABLE customization ADD FOREIGN KEY (id_product_attribute) REFERENCES product_attribute (id_product_attribute);
ALTER TABLE customization ADD FOREIGN KEY (id_product) REFERENCES product (id_product);
ALTER TABLE accessory ADD FOREIGN KEY (id_product_1) REFERENCES product (id_product);
ALTER TABLE accessory ADD FOREIGN KEY (id_product_2) REFERENCES product (id_product);
ALTER TABLE address ADD FOREIGN KEY (id_country) REFERENCES country (id_country);
ALTER TABLE address ADD FOREIGN KEY (id_state) REFERENCES state (id_state);
ALTER TABLE address ADD FOREIGN KEY (id_manufacturer) REFERENCES manufacturer (id_manufacturer);
ALTER TABLE address ADD FOREIGN KEY (id_supplier) REFERENCES supplier (id_supplier);
ALTER TABLE attribute ADD FOREIGN KEY (id_attribute_group) REFERENCES attribute_group (id_attribute_group);
ALTER TABLE attribute_impact ADD FOREIGN KEY (id_product) REFERENCES product (id_product);
ALTER TABLE attribute_impact ADD FOREIGN KEY (id_attribute) REFERENCES attribute (id_attribute);
ALTER TABLE category ADD FOREIGN KEY (id_parent) REFERENCES category (id_category);
ALTER TABLE category_group ADD FOREIGN KEY (id_category) REFERENCES category (id_category);
ALTER TABLE category_group ADD FOREIGN KEY (id_group) REFERENCES _group (id_group);
ALTER TABLE category_product ADD FOREIGN KEY (id_category) REFERENCES category (id_category);
ALTER TABLE category_product ADD FOREIGN KEY (id_product) REFERENCES product (id_product);
ALTER TABLE country ADD FOREIGN KEY (id_zone) REFERENCES zone (id_zone);
ALTER TABLE feature_value ADD FOREIGN KEY (id_feature) REFERENCES feature (id_feature);
ALTER TABLE image ADD FOREIGN KEY (id_product) REFERENCES product (id_product);
ALTER TABLE product ADD FOREIGN KEY (id_supplier) REFERENCES supplier (id_supplier);
ALTER TABLE product ADD FOREIGN KEY (id_manufacturer) REFERENCES manufacturer (id_manufacturer);
ALTER TABLE product ADD FOREIGN KEY (id_tax) REFERENCES tax (id_tax);
ALTER TABLE product_attribute ADD FOREIGN KEY (id_product) REFERENCES product (id_product);
ALTER TABLE product_attribute_image ADD FOREIGN KEY (id_image) REFERENCES image (id_image);
ALTER TABLE tax_state ADD FOREIGN KEY (id_tax) REFERENCES tax (id_tax);
ALTER TABLE tax_state ADD FOREIGN KEY (id_state) REFERENCES state (id_state);
ALTER TABLE tax_zone ADD FOREIGN KEY (id_tax) REFERENCES tax (id_tax);
ALTER TABLE tax_zone ADD FOREIGN KEY (id_zone) REFERENCES zone (id_zone);
ALTER TABLE state ADD FOREIGN KEY (id_country) REFERENCES country (id_country);
ALTER TABLE state ADD FOREIGN KEY (id_zone) REFERENCES zone (id_zone);
