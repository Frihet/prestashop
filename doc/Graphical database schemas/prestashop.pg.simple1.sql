SET NAMES 'utf8';
CREATE TABLE access (
  id_profile int NOT NULL,
  id_tab int NOT NULL,
  PRIMARY KEY (id_profile,id_tab)
);
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
CREATE TABLE alias (
  id_alias serial NOT NULL,
  PRIMARY KEY (id_alias)
);
CREATE TABLE attachment (
  id_attachment serial NOT NULL,
  PRIMARY KEY (id_attachment)
);
CREATE TABLE attachment_lang (
  id_attachment serial NOT NULL,
  id_lang int NOT NULL,
  PRIMARY KEY (id_attachment, id_lang)
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
CREATE TABLE attribute_group_lang (
  id_attribute_group int NOT NULL,
  id_lang int NOT NULL,
  PRIMARY KEY (id_attribute_group,id_lang)
);
CREATE TABLE attribute_impact (
  id_attribute_impact serial NOT NULL,
  id_product int NOT NULL,
  id_attribute int NOT NULL,
  PRIMARY KEY (id_attribute_impact)
);
CREATE TABLE attribute_lang (
  id_attribute int NOT NULL,
  id_lang int NOT NULL,
  PRIMARY KEY (id_attribute,id_lang)
);
CREATE TABLE block_cms (
  id_block int NOT NULL,
  id_cms int NOT NULL,
  PRIMARY KEY (id_block,id_cms)
);
CREATE TABLE carrier (
  id_carrier serial NOT NULL,
  id_tax int default '0',
  PRIMARY KEY (id_carrier)
);
CREATE TABLE carrier_lang (
  id_carrier int NOT NULL,
  id_lang int NOT NULL
);
CREATE TABLE carrier_zone (
  id_carrier int NOT NULL,
  id_zone int NOT NULL,
  PRIMARY KEY (id_carrier,id_zone)
);
CREATE TABLE cart (
  id_cart serial NOT NULL,
  id_carrier int NOT NULL,
  id_lang int NOT NULL,
  id_address_delivery int NOT NULL,
  id_address_invoice int NOT NULL,
  id_currency int NOT NULL,
  id_customer int NOT NULL,
  id_guest int NOT NULL,
  PRIMARY KEY (id_cart)
);
CREATE TABLE cart_discount (
  id_cart int NOT NULL,
  id_discount int NOT NULL
);
CREATE TABLE cart_product (
  id_cart int NOT NULL,
  id_product int NOT NULL,
  id_product_attribute int default NULL
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
CREATE TABLE category_lang (
  id_category int NOT NULL,
  id_lang int NOT NULL
);
CREATE TABLE category_product (
  id_category int NOT NULL,
  id_product int NOT NULL
);
CREATE TABLE cms (
  id_cms serial NOT NULL,
  PRIMARY KEY (id_cms)
);
CREATE TABLE cms_lang (
  id_cms serial NOT NULL,
  id_lang int NOT NULL,
  PRIMARY KEY (id_cms,id_lang)
);
CREATE TABLE configuration (
  id_configuration serial NOT NULL,
  PRIMARY KEY (id_configuration)
);
CREATE TABLE configuration_lang (
  id_configuration int NOT NULL,
  id_lang int NOT NULL,
  PRIMARY KEY (id_configuration,id_lang)
);
CREATE TABLE connections (
  id_connections serial NOT NULL,
  id_guest int NOT NULL,
  id_page int NOT NULL,
  PRIMARY KEY (id_connections)
);
CREATE TABLE connections_page (
  id_connections int NOT NULL,
  id_page int NOT NULL,
  PRIMARY KEY (id_connections,id_page)
);
CREATE TABLE connections_source (
  id_connections_source serial NOT NULL,
  id_connections int NOT NULL,
  PRIMARY KEY (id_connections_source)
);
CREATE TABLE contact (
  id_contact serial NOT NULL,
  PRIMARY KEY (id_contact)
);
CREATE TABLE contact_lang (
  id_contact int NOT NULL,
  id_lang int NOT NULL
);
CREATE TABLE country (
  id_country serial NOT NULL,
  id_zone int NOT NULL,
  PRIMARY KEY (id_country)
);
CREATE TABLE country_lang (
  id_country int NOT NULL,
  id_lang int NOT NULL
);
CREATE TABLE currency (
  id_currency serial NOT NULL,
  PRIMARY KEY (id_currency)
);
CREATE TABLE customer (
  id_customer serial NOT NULL,
  id_gender int NOT NULL,
  PRIMARY KEY (id_customer)
);
CREATE TABLE customer_group (
  id_customer int NOT NULL,
  id_group int NOT NULL,
  PRIMARY KEY (id_customer,id_group)
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
CREATE TABLE customization_field_lang (
  id_customization_field int NOT NULL,
  id_lang int NOT NULL,
  PRIMARY KEY (id_customization_field,id_lang)
);
CREATE TABLE customized_data (
  id_customization int NOT NULL,
  PRIMARY KEY (id_customization)
);
CREATE TABLE date_range (
  id_date_range serial NOT NULL,
  PRIMARY KEY (id_date_range)
);
CREATE TABLE delivery (
  id_delivery serial NOT NULL,
  id_carrier int NOT NULL,
  id_range_price int default NULL,
  id_range_weight int default NULL,
  id_zone int NOT NULL,
  PRIMARY KEY (id_delivery)
);
CREATE TABLE discount (
  id_discount serial NOT NULL,
  id_discount_type int NOT NULL,
  id_customer int NOT NULL,
  PRIMARY KEY (id_discount)
);
CREATE TABLE discount_category (
  id_category int NOT NULL,
  id_discount int NOT NULL,
  PRIMARY KEY (id_category, id_discount)
);
CREATE TABLE discount_lang (
  id_discount int NOT NULL,
  id_lang int NOT NULL,
  PRIMARY KEY (id_discount,id_lang)
);
CREATE TABLE discount_quantity (
  id_discount_quantity serial NOT NULL,
  id_discount_type int NOT NULL,
  id_product int NOT NULL,
  id_product_attribute int default NULL,
  PRIMARY KEY (id_discount_quantity)
);
CREATE TABLE discount_type (
  id_discount_type serial NOT NULL,
  PRIMARY KEY (id_discount_type)
);
CREATE TABLE discount_type_lang (
  id_discount_type int NOT NULL,
  id_lang int NOT NULL,
  PRIMARY KEY (id_discount_type,id_lang)
);
CREATE TABLE employee (
  id_employee serial NOT NULL,
  id_profile int NOT NULL,
  PRIMARY KEY (id_employee)
);
CREATE TABLE feature (
  id_feature serial NOT NULL,
  PRIMARY KEY (id_feature)
);
CREATE TABLE feature_lang (
  id_feature int NOT NULL,
  id_lang int NOT NULL,
  PRIMARY KEY (id_feature,id_lang)
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
CREATE TABLE feature_value_lang (
  id_feature_value int NOT NULL,
  id_lang int NOT NULL,
  PRIMARY KEY (id_feature_value,id_lang)
);
CREATE TABLE _group (
  id_group serial NOT NULL,
  PRIMARY KEY (id_group)
);
CREATE TABLE _group_lang (
  id_group int NOT NULL,
  id_lang int NOT NULL
);
CREATE TABLE guest (
  id_guest serial NOT NULL,
  id_operating_system int default NULL,
  id_web_browser int default NULL,
  id_customer int default NULL,
  PRIMARY KEY (id_guest)
);
CREATE TABLE hook (
  id_hook serial NOT NULL,
  PRIMARY KEY (id_hook)
);
CREATE TABLE hook_module (
  id_module int NOT NULL,
  id_hook int NOT NULL,
  PRIMARY KEY (id_module,id_hook)
);
CREATE TABLE hook_module_exceptions (
  id_hook_module_exceptions serial NOT NULL,
  id_module int NOT NULL,
  id_hook int NOT NULL,
  PRIMARY KEY (id_hook_module_exceptions)
);
CREATE TABLE image (
  id_image serial NOT NULL,
  id_product int NOT NULL,
  PRIMARY KEY (id_image)
);
CREATE TABLE image_lang (
  id_image int NOT NULL,
  id_lang int NOT NULL
);
CREATE TABLE image_type (
  id_image_type serial NOT NULL,
  PRIMARY KEY (id_image_type)
);
CREATE TABLE lang (
  id_lang serial NOT NULL,
  PRIMARY KEY (id_lang)
);
CREATE TABLE manufacturer (
  id_manufacturer serial NOT NULL,
  PRIMARY KEY (id_manufacturer)
);
CREATE TABLE manufacturer_lang (
  id_manufacturer int NOT NULL,
  id_lang int NOT NULL,
  PRIMARY KEY (id_manufacturer,id_lang)
);
CREATE TABLE message (
  id_message serial NOT NULL,
  id_cart int default NULL,
  id_customer int NOT NULL,
  id_employee int default NULL,
  id_order int NOT NULL,
  PRIMARY KEY (id_message)
);
CREATE TABLE message_readed (
  id_message int NOT NULL,
  id_employee int NOT NULL,
  PRIMARY KEY (id_message,id_employee)
);
CREATE TABLE meta (
  id_meta serial NOT NULL,
  PRIMARY KEY (id_meta)
);
CREATE TABLE meta_lang (
  id_meta int NOT NULL,
  id_lang int NOT NULL,
  PRIMARY KEY (id_meta,id_lang)
);
CREATE TABLE module (
  id_module serial NOT NULL,
  PRIMARY KEY (id_module)
);
CREATE TABLE module_country (
  id_module int NOT NULL,
  id_country int NOT NULL,
  PRIMARY KEY (id_module,id_country)
);
CREATE TABLE module_currency (
  id_module int NOT NULL,
  id_currency int NOT NULL,
  PRIMARY KEY (id_module,id_currency)
);
CREATE TABLE module_group (
  id_module int NOT NULL,
  id_group int NOT NULL,
  PRIMARY KEY (id_module,id_group)
);
CREATE TABLE operating_system (
  id_operating_system serial NOT NULL,
  PRIMARY KEY (id_operating_system)
);
CREATE TABLE orders (
  id_order serial NOT NULL,
  id_carrier int NOT NULL,
  id_lang int NOT NULL,
  id_customer int NOT NULL,
  id_cart int NOT NULL,
  id_currency int NOT NULL,
  id_address_delivery int NOT NULL,
  id_address_invoice int NOT NULL,
  PRIMARY KEY (id_order)
);
CREATE TABLE order_detail (
  id_order_detail serial NOT NULL,
  id_order int NOT NULL,
  PRIMARY KEY (id_order_detail)
);
CREATE TABLE order_discount (
  id_order_discount serial NOT NULL,
  id_order int NOT NULL,
  id_discount int NOT NULL,
  PRIMARY KEY (id_order_discount)
);
CREATE TABLE order_history (
  id_order_history serial NOT NULL,
  id_employee int NOT NULL,
  id_order int NOT NULL,
  id_order_state int NOT NULL,
  PRIMARY KEY (id_order_history)
);
CREATE TABLE order_message (
  id_order_message serial NOT NULL,
  PRIMARY KEY (id_order_message)
);
CREATE TABLE order_message_lang (
  id_order_message int NOT NULL,
  id_lang int NOT NULL,
  PRIMARY KEY (id_order_message,id_lang)
);
CREATE TABLE order_return (
  id_order_return serial NOT NULL,
  id_customer int NOT NULL,
  id_order int NOT NULL,
  PRIMARY KEY (id_order_return)
);
CREATE TABLE order_return_detail (
  id_order_return int NOT NULL,
  id_order_detail int NOT NULL,
  id_customization int NOT NULL default '0',
  PRIMARY KEY (id_order_return,id_order_detail,id_customization)
);
CREATE TABLE order_return_state (
  id_order_return_state serial NOT NULL,
  PRIMARY KEY (id_order_return_state)
);
CREATE TABLE order_return_state_lang (
  id_order_return_state int NOT NULL,
  id_lang int NOT NULL
);
CREATE TABLE order_slip (
  id_order_slip serial NOT NULL,
  id_customer int NOT NULL,
  id_order int NOT NULL,
  PRIMARY KEY (id_order_slip)
);
CREATE TABLE order_slip_detail (
  id_order_slip int NOT NULL,
  id_order_detail int NOT NULL,
  PRIMARY KEY (id_order_slip,id_order_detail)
);
CREATE TABLE order_state (
  id_order_state serial NOT NULL,
  PRIMARY KEY (id_order_state)
);
CREATE TABLE order_state_lang (
  id_order_state int NOT NULL,
  id_lang int NOT NULL
);
CREATE TABLE pack (
  id_product_pack int NOT NULL,
  id_product_item int NOT NULL,
  PRIMARY KEY (id_product_pack,id_product_item)
);
CREATE TABLE page (
  id_page serial NOT NULL,
  id_page_type int NOT NULL,
  id_object int default NULL,
  PRIMARY KEY (id_page)
);
CREATE TABLE page_type (
  id_page_type serial NOT NULL,
  PRIMARY KEY (id_page_type)
);
CREATE TABLE page_viewed (
  id_page int NOT NULL,
  id_date_range int NOT NULL,
  PRIMARY KEY (id_page,id_date_range)
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
CREATE TABLE product_lang (
  id_product int NOT NULL,
  id_lang int NOT NULL
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
CREATE TABLE profile (
  id_profile serial NOT NULL,
  PRIMARY KEY (id_profile)
);
CREATE TABLE profile_lang (
  id_lang int NOT NULL,
  id_profile int NOT NULL,
  PRIMARY KEY (id_profile,id_lang)
);
CREATE TABLE quick_access (
  id_quick_access serial NOT NULL,
  PRIMARY KEY (id_quick_access)
);
CREATE TABLE quick_access_lang (
  id_quick_access int NOT NULL,
  id_lang int NOT NULL,
  PRIMARY KEY (id_quick_access,id_lang)
);
CREATE TABLE range_price (
  id_range_price serial NOT NULL,
  id_carrier int NOT NULL,
  PRIMARY KEY (id_range_price)
);
CREATE TABLE range_weight (
  id_range_weight serial NOT NULL,
  id_carrier int NOT NULL,
  PRIMARY KEY (id_range_weight)
);
CREATE TABLE referrer (
  id_referrer serial NOT NULL,
  PRIMARY KEY (id_referrer)
);
CREATE TABLE referrer_cache (
  id_connections_source int NOT NULL,
  id_referrer int NOT NULL,
  PRIMARY KEY (id_connections_source, id_referrer)
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
CREATE TABLE scene_lang (
  id_scene int NOT NULL,
  id_lang int NOT NULL,
  PRIMARY KEY (id_scene,id_lang)
);
CREATE TABLE scene_products (
  id_scene int NOT NULL,
  id_product int NOT NULL,
  PRIMARY KEY (id_scene, id_product)
);
CREATE TABLE search_engine (
  id_search_engine serial NOT NULL,
  PRIMARY KEY (id_search_engine)
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
CREATE TABLE subdomain (
  id_subdomain serial NOT NULL,
  PRIMARY KEY (id_subdomain)
);
CREATE TABLE supplier (
  id_supplier serial NOT NULL,
  PRIMARY KEY (id_supplier)
);
CREATE TABLE supplier_lang (
  id_supplier int NOT NULL,
  id_lang int NOT NULL,
  PRIMARY KEY (id_supplier,id_lang)
);
CREATE TABLE tab (
  id_tab serial NOT NULL,
  id_parent int NOT NULL,
  PRIMARY KEY (id_tab)
);
CREATE TABLE tab_lang (
  id_lang int NOT NULL,
  id_tab int NOT NULL,
  PRIMARY KEY (id_tab,id_lang)
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
CREATE TABLE tax_lang (
  id_tax int NOT NULL,
  id_lang int NOT NULL
);
CREATE TABLE tax_state (
  id_tax int NOT NULL,
  id_state int NOT NULL
);
CREATE TABLE tax_zone (
  id_tax int NOT NULL,
  id_zone int NOT NULL
);
CREATE TABLE timezone (
	id_timezone serial NOT NULL,
	name VARCHAR(32) NOT NULL,
	PRIMARY KEY (id_timezone)
);
CREATE TABLE web_browser (
  id_web_browser serial NOT NULL,
  PRIMARY KEY (id_web_browser)
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
ALTER TABLE tab ADD FOREIGN KEY (id_parent) REFERENCES tab (id_tab);
ALTER TABLE search_index ADD FOREIGN KEY (id_product) REFERENCES product (id_product);
ALTER TABLE search_index ADD FOREIGN KEY (id_word) REFERENCES search_word (id_word);
ALTER TABLE scene_products ADD FOREIGN KEY (id_scene) REFERENCES scene (id_scene);
ALTER TABLE scene_products ADD FOREIGN KEY (id_product) REFERENCES product (id_product);
ALTER TABLE scene_category ADD FOREIGN KEY (id_scene) REFERENCES scene (id_scene);
ALTER TABLE scene_category ADD FOREIGN KEY (id_category) REFERENCES category (id_category);
ALTER TABLE referrer_cache ADD FOREIGN KEY (id_referrer) REFERENCES referrer (id_referrer);
ALTER TABLE referrer_cache ADD FOREIGN KEY (id_connections_source) REFERENCES connections_source (id_connections_source);
ALTER TABLE range_weight ADD FOREIGN KEY (id_carrier) REFERENCES carrier (id_carrier);
ALTER TABLE range_price ADD FOREIGN KEY (id_carrier) REFERENCES carrier (id_carrier);
ALTER TABLE product_tag ADD FOREIGN KEY (id_product) REFERENCES product (id_product);
ALTER TABLE product_tag ADD FOREIGN KEY (id_tag) REFERENCES tag (id_tag);
ALTER TABLE product_sale ADD FOREIGN KEY (id_product) REFERENCES product (id_product);
ALTER TABLE product_download ADD FOREIGN KEY (id_product) REFERENCES product (id_product);
ALTER TABLE product_attribute_combination ADD FOREIGN KEY (id_attribute) REFERENCES attribute (id_attribute);
ALTER TABLE product_attribute_combination ADD FOREIGN KEY (id_product_attribute) REFERENCES product_attribute (id_product_attribute);
ALTER TABLE product_attachment ADD FOREIGN KEY (id_product) REFERENCES product (id_product);
ALTER TABLE product_attachment ADD FOREIGN KEY (id_attachment) REFERENCES attachment (id_attachment);
ALTER TABLE page_viewed ADD FOREIGN KEY (id_page) REFERENCES page (id_page);
ALTER TABLE page_viewed ADD FOREIGN KEY (id_date_range) REFERENCES date_range (id_date_range);
ALTER TABLE order_slip_detail ADD FOREIGN KEY (id_order_detail) REFERENCES order_detail (id_order_detail);
ALTER TABLE order_return_detail ADD FOREIGN KEY (id_order_detail) REFERENCES order_detail (id_order_detail);
ALTER TABLE order_return_detail ADD FOREIGN KEY (id_customization) REFERENCES customization (id_customization);
ALTER TABLE module_group ADD FOREIGN KEY (id_module) REFERENCES module (id_module);
ALTER TABLE module_group ADD FOREIGN KEY (id_group) REFERENCES _group (id_group);
ALTER TABLE module_currency ADD FOREIGN KEY (id_module) REFERENCES module (id_module);
ALTER TABLE module_currency ADD FOREIGN KEY (id_currency) REFERENCES currency (id_currency);
ALTER TABLE module_country ADD FOREIGN KEY (id_module) REFERENCES module (id_module);
ALTER TABLE module_country ADD FOREIGN KEY (id_country) REFERENCES country (id_country);
ALTER TABLE message_readed ADD FOREIGN KEY (id_message) REFERENCES message (id_message);
ALTER TABLE message_readed ADD FOREIGN KEY (id_employee) REFERENCES employee (id_employee);
ALTER TABLE hook_module_exceptions ADD FOREIGN KEY (id_module) REFERENCES module (id_module);
ALTER TABLE hook_module_exceptions ADD FOREIGN KEY (id_hook) REFERENCES hook (id_hook);
ALTER TABLE hook_module ADD FOREIGN KEY (id_module) REFERENCES module (id_module);
ALTER TABLE feature_product ADD FOREIGN KEY (id_feature) REFERENCES feature (id_feature);
ALTER TABLE feature_product ADD FOREIGN KEY (id_product) REFERENCES product (id_product);
ALTER TABLE employee ADD FOREIGN KEY (id_profile) REFERENCES profile (id_profile);
ALTER TABLE discount_quantity ADD FOREIGN KEY (id_discount_type) REFERENCES discount_type (id_discount_type);
ALTER TABLE discount_quantity ADD FOREIGN KEY (id_product) REFERENCES product (id_product);
ALTER TABLE discount_quantity ADD FOREIGN KEY (id_product_attribute) REFERENCES product_attribute (id_product_attribute);
ALTER TABLE discount_category ADD FOREIGN KEY (id_discount) REFERENCES discount (id_discount);
ALTER TABLE discount_category ADD FOREIGN KEY (id_category) REFERENCES category (id_category);
ALTER TABLE customization_field ADD FOREIGN KEY (id_product) REFERENCES product (id_product);
ALTER TABLE customization ADD FOREIGN KEY (id_product_attribute) REFERENCES product_attribute (id_product_attribute);
ALTER TABLE customization ADD FOREIGN KEY (id_cart) REFERENCES cart (id_cart);
ALTER TABLE customization ADD FOREIGN KEY (id_product) REFERENCES product (id_product);
ALTER TABLE customer_group ADD FOREIGN KEY (id_customer) REFERENCES customer (id_customer);
ALTER TABLE customer_group ADD FOREIGN KEY (id_group) REFERENCES _group (id_group);
ALTER TABLE connections_page ADD FOREIGN KEY (id_connections) REFERENCES connections (id_connections);
ALTER TABLE connections_page ADD FOREIGN KEY (id_page) REFERENCES page (id_page);
ALTER TABLE carrier_zone ADD FOREIGN KEY (id_carrier) REFERENCES carrier (id_carrier);
ALTER TABLE carrier_zone ADD FOREIGN KEY (id_zone) REFERENCES zone (id_zone);
ALTER TABLE block_cms ADD FOREIGN KEY (id_cms) REFERENCES cms (id_cms);
ALTER TABLE access ADD FOREIGN KEY (id_profile) REFERENCES profile (id_profile);
ALTER TABLE access ADD FOREIGN KEY (id_tab) REFERENCES tab (id_tab);
ALTER TABLE accessory ADD FOREIGN KEY (id_product_1) REFERENCES product (id_product);
ALTER TABLE accessory ADD FOREIGN KEY (id_product_2) REFERENCES product (id_product);
ALTER TABLE address ADD FOREIGN KEY (id_customer) REFERENCES customer (id_customer);
ALTER TABLE address ADD FOREIGN KEY (id_country) REFERENCES country (id_country);
ALTER TABLE address ADD FOREIGN KEY (id_state) REFERENCES state (id_state);
ALTER TABLE address ADD FOREIGN KEY (id_manufacturer) REFERENCES manufacturer (id_manufacturer);
ALTER TABLE address ADD FOREIGN KEY (id_supplier) REFERENCES supplier (id_supplier);
ALTER TABLE attribute ADD FOREIGN KEY (id_attribute_group) REFERENCES attribute_group (id_attribute_group);
ALTER TABLE attribute_impact ADD FOREIGN KEY (id_product) REFERENCES product (id_product);
ALTER TABLE attribute_impact ADD FOREIGN KEY (id_attribute) REFERENCES attribute (id_attribute);
ALTER TABLE cart ADD FOREIGN KEY (id_carrier) REFERENCES carrier (id_carrier);
ALTER TABLE cart ADD FOREIGN KEY (id_address_delivery) REFERENCES address (id_address);
ALTER TABLE cart ADD FOREIGN KEY (id_address_invoice) REFERENCES address (id_address);
ALTER TABLE cart ADD FOREIGN KEY (id_currency) REFERENCES currency (id_currency);
ALTER TABLE cart ADD FOREIGN KEY (id_customer) REFERENCES customer (id_customer);
ALTER TABLE cart ADD FOREIGN KEY (id_guest) REFERENCES guest (id_guest);
ALTER TABLE cart_discount ADD FOREIGN KEY (id_cart) REFERENCES cart (id_cart);
ALTER TABLE cart_discount ADD FOREIGN KEY (id_discount) REFERENCES discount (id_discount);
ALTER TABLE cart_discount ADD FOREIGN KEY (id_discount) REFERENCES discount (id_discount);
ALTER TABLE cart_product ADD FOREIGN KEY (id_cart) REFERENCES cart (id_cart);
ALTER TABLE cart_product ADD FOREIGN KEY (id_product) REFERENCES product (id_product);
ALTER TABLE category ADD FOREIGN KEY (id_parent) REFERENCES category (id_category);
ALTER TABLE category_group ADD FOREIGN KEY (id_category) REFERENCES category (id_category);
ALTER TABLE category_group ADD FOREIGN KEY (id_group) REFERENCES _group (id_group);
ALTER TABLE category_product ADD FOREIGN KEY (id_category) REFERENCES category (id_category);
ALTER TABLE category_product ADD FOREIGN KEY (id_product) REFERENCES product (id_product);
ALTER TABLE connections ADD FOREIGN KEY (id_guest) REFERENCES guest (id_guest);
ALTER TABLE connections ADD FOREIGN KEY (id_page) REFERENCES page (id_page);
ALTER TABLE connections_source ADD FOREIGN KEY (id_connections) REFERENCES connections (id_connections);
ALTER TABLE country ADD FOREIGN KEY (id_zone) REFERENCES zone (id_zone);
ALTER TABLE delivery ADD FOREIGN KEY (id_zone) REFERENCES zone (id_zone);
ALTER TABLE delivery ADD FOREIGN KEY (id_carrier) REFERENCES carrier (id_carrier);
ALTER TABLE delivery ADD FOREIGN KEY (id_range_price) REFERENCES range_price (id_range_price);
ALTER TABLE delivery ADD FOREIGN KEY (id_range_weight) REFERENCES range_weight (id_range_weight);
ALTER TABLE discount ADD FOREIGN KEY (id_customer) REFERENCES customer (id_customer);
ALTER TABLE feature_value ADD FOREIGN KEY (id_feature) REFERENCES feature (id_feature);
ALTER TABLE guest ADD FOREIGN KEY (id_customer) REFERENCES customer (id_customer);
ALTER TABLE guest ADD FOREIGN KEY (id_operating_system) REFERENCES operating_system (id_operating_system);
ALTER TABLE guest ADD FOREIGN KEY (id_web_browser) REFERENCES web_browser (id_web_browser);
ALTER TABLE hook_module ADD FOREIGN KEY (id_hook) REFERENCES hook (id_hook);
ALTER TABLE image ADD FOREIGN KEY (id_product) REFERENCES product (id_product);
ALTER TABLE message ADD FOREIGN KEY (id_order) REFERENCES orders (id_order);
ALTER TABLE message ADD FOREIGN KEY (id_cart) REFERENCES cart (id_cart);
ALTER TABLE message ADD FOREIGN KEY (id_customer) REFERENCES customer (id_customer);
ALTER TABLE message ADD FOREIGN KEY (id_employee) REFERENCES employee (id_employee);
ALTER TABLE orders ADD FOREIGN KEY (id_customer) REFERENCES customer (id_customer);
ALTER TABLE orders ADD FOREIGN KEY (id_cart) REFERENCES cart (id_cart);
ALTER TABLE orders ADD FOREIGN KEY (id_carrier) REFERENCES carrier (id_carrier);
ALTER TABLE orders ADD FOREIGN KEY (id_address_delivery) REFERENCES address (id_address);
ALTER TABLE orders ADD FOREIGN KEY (id_address_invoice) REFERENCES address (id_address);
ALTER TABLE orders ADD FOREIGN KEY (id_currency) REFERENCES currency (id_currency);
ALTER TABLE order_detail ADD FOREIGN KEY (id_order) REFERENCES orders (id_order);
ALTER TABLE order_discount ADD FOREIGN KEY (id_order) REFERENCES orders (id_order);
ALTER TABLE order_discount ADD FOREIGN KEY (id_discount) REFERENCES discount (id_discount);
ALTER TABLE order_history ADD FOREIGN KEY (id_order) REFERENCES orders (id_order);
ALTER TABLE order_history ADD FOREIGN KEY (id_order_state) REFERENCES order_state (id_order_state);
ALTER TABLE order_history ADD FOREIGN KEY (id_employee) REFERENCES employee (id_employee);
ALTER TABLE order_return ADD FOREIGN KEY (id_customer) REFERENCES customer (id_customer);
ALTER TABLE order_return ADD FOREIGN KEY (id_order) REFERENCES orders (id_order);
ALTER TABLE order_slip ADD FOREIGN KEY (id_customer) REFERENCES customer (id_customer);
ALTER TABLE order_slip ADD FOREIGN KEY (id_order) REFERENCES orders (id_order);
ALTER TABLE page ADD FOREIGN KEY (id_page_type) REFERENCES page_type (id_page_type);
ALTER TABLE product ADD FOREIGN KEY (id_supplier) REFERENCES supplier (id_supplier);
ALTER TABLE product ADD FOREIGN KEY (id_manufacturer) REFERENCES manufacturer (id_manufacturer);
ALTER TABLE product ADD FOREIGN KEY (id_tax) REFERENCES tax (id_tax);
ALTER TABLE product_attribute ADD FOREIGN KEY (id_product) REFERENCES product (id_product);
ALTER TABLE product_attribute_image ADD FOREIGN KEY (id_image) REFERENCES image (id_image);
ALTER TABLE tax_state ADD FOREIGN KEY (id_tax) REFERENCES tax (id_tax);
ALTER TABLE tax_state ADD FOREIGN KEY (id_state) REFERENCES state (id_state);
ALTER TABLE tax_zone ADD FOREIGN KEY (id_tax) REFERENCES tax (id_tax);
ALTER TABLE tax_zone ADD FOREIGN KEY (id_zone) REFERENCES zone (id_zone);
ALTER TABLE carrier ADD FOREIGN KEY (id_tax) REFERENCES tax (id_tax);
ALTER TABLE state ADD FOREIGN KEY (id_country) REFERENCES country (id_country);
ALTER TABLE state ADD FOREIGN KEY (id_zone) REFERENCES zone (id_zone);
