SET NAMES 'utf8';

CREATE TABLE access (
  id_profile int NOT NULL,
  id_tab int NOT NULL,
  view int NOT NULL,
  add int NOT NULL,
  edit int NOT NULL,
  delete int NOT NULL,
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
  alias varchar(32) NOT NULL,
  company varchar(32) default NULL,
  lastname varchar(32) NOT NULL,
  firstname varchar(32) NOT NULL,
  address1 varchar(128) NOT NULL,
  address2 varchar(128) default NULL,
  postcode varchar(12) default NULL,
  city varchar(64) NOT NULL,
  other text,
  phone varchar(16) default NULL,
  phone_mobile varchar(16) default NULL,
  date_add timestamp NOT NULL,
  date_upd timestamp NOT NULL,
  active int NOT NULL default '1',
  deleted int NOT NULL default '0',
  PRIMARY KEY (id_address)
);

CREATE TABLE alias (
  id_alias serial NOT NULL,
  alias varchar(255) NOT NULL,
  search varchar(255) NOT NULL,
  active int NOT NULL default '1',
  PRIMARY KEY (id_alias),
  UNIQUE (alias)
);

CREATE TABLE attachment (
  id_attachment serial NOT NULL,
  file varchar(40) NOT NULL,
  mime varchar(32) NOT NULL,
  PRIMARY KEY (id_attachment)
);

CREATE TABLE attachment_lang (
  id_attachment serial NOT NULL,
  id_lang int NOT NULL,
  name varchar(32) default NULL,
  description TEXT,
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
  color varchar(32) default NULL,
  PRIMARY KEY (id_attribute)
);

CREATE TABLE attribute_group (
  id_attribute_group serial NOT NULL,
  is_color_group int NOT NULL default '0',
  PRIMARY KEY (id_attribute_group)
);

CREATE TABLE attribute_group_lang (
  id_attribute_group int NOT NULL,
  id_lang int NOT NULL,
  name varchar(128) NOT NULL,
  public_name varchar(64) NOT NULL,
  PRIMARY KEY (id_attribute_group,id_lang)
);

CREATE TABLE attribute_impact (
  id_attribute_impact serial NOT NULL,
  id_product int NOT NULL,
  id_attribute int NOT NULL,
  weight float NOT NULL,
  price decimal(10,2) NOT NULL,
  PRIMARY KEY (id_attribute_impact)
);

CREATE TABLE attribute_lang (
  id_attribute int NOT NULL,
  id_lang int NOT NULL,
  name varchar(128) NOT NULL,
  PRIMARY KEY (id_attribute,id_lang),
  UNIQUE (name)
);

CREATE TABLE block_cms (
  id_block int NOT NULL,
  id_cms int NOT NULL,
  PRIMARY KEY (id_block,id_cms)
);

CREATE TABLE carrier (
  id_carrier serial NOT NULL,
  id_tax int default '0',
  name varchar(64) NOT NULL,
  url varchar(255) default NULL,
  active int NOT NULL default '0',
  deleted int NOT NULL default '0',
  shipping_handling int NOT NULL default '1',
  range_behavior int NOT NULL default '0',
  is_module int NOT NULL default '0',
  PRIMARY KEY (id_carrier),
  UNIQUE (deleted),
  UNIQUE (active)
);

CREATE TABLE carrier_lang (
  id_carrier int NOT NULL,
  id_lang int NOT NULL,
  delay varchar(128) default NULL
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
  recyclable int NOT NULL default '1',
  gift int NOT NULL default '0',
  gift_message text,
  date_add timestamp NOT NULL,
  date_upd timestamp NOT NULL,
  PRIMARY KEY (id_cart)
);

CREATE TABLE cart_discount (
  id_cart int NOT NULL,
  id_discount int NOT NULL
);

CREATE TABLE cart_product (
  id_cart int NOT NULL,
  id_product int NOT NULL,
  id_product_attribute int default NULL,
  quantity int NOT NULL default '0',
  date_add timestamp NOT NULL
);

CREATE TABLE category (
  id_category serial NOT NULL,
  id_parent int NOT NULL,
  level_depth int NOT NULL default '0',
  active int NOT NULL default '0',
  base_url varchar(256) default NULL,
  theme varchar(32) default NULL,
  date_add timestamp NOT NULL,
  date_upd timestamp NOT NULL,
  PRIMARY KEY (id_category)
);

CREATE TABLE category_group (
  id_category int NOT NULL,
  id_group int NOT NULL
);

CREATE TABLE category_lang (
  id_category int NOT NULL,
  id_lang int NOT NULL,
  name varchar(128) NOT NULL,
  description text,
  link_rewrite varchar(128) NOT NULL,
  meta_title varchar(128) default NULL,
  meta_keywords varchar(128) default NULL,
  meta_description varchar(128) default NULL,
  UNIQUE (name)
);

CREATE TABLE category_product (
  id_category int NOT NULL,
  id_product int NOT NULL,
  position int NOT NULL default '0'
);

CREATE TABLE cms (
  id_cms serial NOT NULL,
  PRIMARY KEY (id_cms)
);

CREATE TABLE cms_lang (
  id_cms serial NOT NULL,
  id_lang int NOT NULL,
  meta_title varchar(128) NOT NULL,
  meta_description varchar(255) default NULL,
  meta_keywords varchar(255) default NULL,
  content varchar,
  link_rewrite varchar(128) NOT NULL,
  PRIMARY KEY (id_cms,id_lang)
);

CREATE TABLE configuration (
  id_configuration serial NOT NULL,
  name varchar(32) NOT NULL,
  value text,
  date_add timestamp NOT NULL,
  date_upd timestamp NOT NULL,
  PRIMARY KEY (id_configuration),
  UNIQUE (name)
);

CREATE TABLE configuration_lang (
  id_configuration int NOT NULL,
  id_lang int NOT NULL,
  value text,
  date_upd timestamp default NULL,
  PRIMARY KEY (id_configuration,id_lang)
);

CREATE TABLE connections (
  id_connections serial NOT NULL,
  id_guest int NOT NULL,
  id_page int NOT NULL,
  ip_address varchar(16) default NULL,
  date_add timestamp NOT NULL,
  http_referer varchar(255) default NULL,
  PRIMARY KEY (id_connections),
  UNIQUE (date_add)
);

CREATE TABLE connections_page (
  id_connections int NOT NULL,
  id_page int NOT NULL,
  time_start timestamp NOT NULL,
  time_end timestamp default NULL,
  PRIMARY KEY (id_connections,id_page,time_start)
);

CREATE TABLE connections_source (
  id_connections_source serial NOT NULL,
  id_connections int NOT NULL,
  http_referer varchar(255) default NULL,
  request_uri varchar(255) default NULL,
  keywords varchar(255) default NULL,
  date_add timestamp NOT NULL,
  PRIMARY KEY (id_connections_source),
  UNIQUE (date_add),
  UNIQUE (http_referer),
  UNIQUE (request_uri)
);

CREATE TABLE contact (
  id_contact serial NOT NULL,
  email varchar(128) NOT NULL,
  position int NOT NULL default '0',
  PRIMARY KEY (id_contact)
);

CREATE TABLE contact_lang (
  id_contact int NOT NULL,
  id_lang int NOT NULL,
  name varchar(32) NOT NULL,
  description text
);

CREATE TABLE country (
  id_country serial NOT NULL,
  id_zone int NOT NULL,
  iso_code varchar(3) NOT NULL,
  active int NOT NULL default '0',
  contains_states int NOT NULL default '0',
  PRIMARY KEY (id_country),
  UNIQUE (iso_code)
);

CREATE TABLE country_lang (
  id_country int NOT NULL,
  id_lang int NOT NULL,
  name varchar(64) NOT NULL
);

CREATE TABLE currency (
  id_currency serial NOT NULL,
  name varchar(32) NOT NULL,
  iso_code varchar(3) NOT NULL default '0',
  sign varchar(8) NOT NULL,
  blank int NOT NULL default '0',
  format int NOT NULL default '0',
  decimals int NOT NULL default '1',
  conversion_rate decimal(13,6) NOT NULL,
  deleted int NOT NULL default '0',
  PRIMARY KEY (id_currency)
);

CREATE TABLE customer (
  id_customer serial NOT NULL,
  id_gender int NOT NULL,
  secure_key varchar(32) NOT NULL default '-1',
  email varchar(128) NOT NULL,
  passwd varchar(32) NOT NULL,
  last_passwd_gen timestamp NOT NULL default CURRENT_TIMESTAMP,
  birthday date default NULL,
  lastname varchar(32) NOT NULL,
  newsletter int NOT NULL default '0',
  ip_registration_newsletter varchar(15) default NULL,
  newsletter_date_add timestamp default NULL,
  optin int NOT NULL default '0',
  firstname varchar(32) NOT NULL,
  active int NOT NULL default '0',
  deleted int NOT NULL default '0',
  date_add timestamp NOT NULL,
  date_upd timestamp NOT NULL,
  PRIMARY KEY (id_customer),
  UNIQUE (email),
  UNIQUE (email),
  UNIQUE (passwd)
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
  quantity int NOT NULL,
  quantity_refunded INT NOT NULL DEFAULT '0',
  quantity_returned INT NOT NULL DEFAULT '0',
--  PRIMARY KEY (id_customization,id_cart,id_product)
  PRIMARY KEY (id_customization)
);

CREATE TABLE customization_field (
  id_customization_field serial NOT NULL,
  id_product int NOT NULL,
  type int NOT NULL,
  required int NOT NULL,
  PRIMARY KEY (id_customization_field)
);

CREATE TABLE customization_field_lang (
  id_customization_field int NOT NULL,
  id_lang int NOT NULL,
  name varchar(255) NOT NULL,
  PRIMARY KEY (id_customization_field,id_lang)
);

CREATE TABLE customized_data (
  id_customization int NOT NULL,
  type int NOT NULL,
  index int NOT NULL,
  value varchar(255) NOT NULL,
  PRIMARY KEY (id_customization,type,index)
);

CREATE TABLE date_range (
  id_date_range serial NOT NULL,
  time_start timestamp NOT NULL,
  time_end timestamp NOT NULL,
  PRIMARY KEY (id_date_range)
);

CREATE TABLE delivery (
  id_delivery serial NOT NULL,
  id_carrier int NOT NULL,
  id_range_price int default NULL,
  id_range_weight int default NULL,
  id_zone int NOT NULL,
  price decimal(10,2) NOT NULL,
  PRIMARY KEY (id_delivery)
);

CREATE TABLE discount (
  id_discount serial NOT NULL,
  id_discount_type int NOT NULL,
  id_customer int NOT NULL,
  name varchar(32) NOT NULL,
  value decimal(10,2) NOT NULL default '0.00',
  quantity int NOT NULL default '0',
  quantity_per_user int NOT NULL default '1',
  cumulable int NOT NULL default '0',
  cumulable_reduction int NOT NULL default '0',
  date_from timestamp NOT NULL,
  date_to timestamp NOT NULL,
  minimal decimal(10,2) default NULL,
  active int NOT NULL default '0',
  PRIMARY KEY (id_discount),
  UNIQUE (name)
);

CREATE TABLE discount_category (
  id_category int NOT NULL,
  id_discount int NOT NULL,
  PRIMARY KEY (id_category, id_discount)
);

CREATE TABLE discount_lang (
  id_discount int NOT NULL,
  id_lang int NOT NULL,
  description text,
  PRIMARY KEY (id_discount,id_lang)
);

CREATE TABLE discount_quantity (
  id_discount_quantity serial NOT NULL,
  id_discount_type int NOT NULL,
  id_product int NOT NULL,
  id_product_attribute int default NULL,
  quantity int NOT NULL,
  value decimal(10,2) NOT NULL,
  PRIMARY KEY (id_discount_quantity)
);

CREATE TABLE discount_type (
  id_discount_type serial NOT NULL,
  PRIMARY KEY (id_discount_type)
);

CREATE TABLE discount_type_lang (
  id_discount_type int NOT NULL,
  id_lang int NOT NULL,
  name varchar(64) NOT NULL,
  PRIMARY KEY (id_discount_type,id_lang)
);

CREATE TABLE employee (
  id_employee serial NOT NULL,
  id_profile int NOT NULL,
  lastname varchar(32) NOT NULL,
  firstname varchar(32) NOT NULL,
  email varchar(128) NOT NULL,
  passwd varchar(32) NOT NULL,
  last_passwd_gen timestamp NOT NULL default CURRENT_TIMESTAMP,
  stats_date_from date default NULL,
  stats_date_to date default NULL,
  active int NOT NULL default '0',
  PRIMARY KEY (id_employee),
  UNIQUE (email),
  UNIQUE (passwd)
);

CREATE TABLE feature (
  id_feature serial NOT NULL,
  PRIMARY KEY (id_feature)
);

CREATE TABLE feature_lang (
  id_feature int NOT NULL,
  id_lang int NOT NULL,
  name varchar(128) default NULL,
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
  custom int default NULL,
  PRIMARY KEY (id_feature_value)
);

CREATE TABLE feature_value_lang (
  id_feature_value int NOT NULL,
  id_lang int NOT NULL,
  value varchar(255) default NULL,
  PRIMARY KEY (id_feature_value,id_lang)
);

CREATE TABLE _group (
  id_group serial NOT NULL,
  reduction decimal(10,2) NOT NULL default '0.00',
  date_add timestamp NOT NULL,
  date_upd timestamp NOT NULL,
  PRIMARY KEY (id_group)
);

CREATE TABLE _group_lang (
  id_group int NOT NULL,
  id_lang int NOT NULL,
  name varchar(32) NOT NULL
);

CREATE TABLE guest (
  id_guest serial NOT NULL,
  id_operating_system int default NULL,
  id_web_browser int default NULL,
  id_customer int default NULL,
  javascript int default '0',
  screen_resolution_x smallint default NULL,
  screen_resolution_y smallint default NULL,
  screen_color int default NULL,
  sun_java int default NULL,
  adobe_flash int default NULL,
  adobe_director int default NULL,
  apple_quicktime int default NULL,
  real_player int default NULL,
  windows_media int default NULL,
  accept_language varchar(8) default NULL,
  PRIMARY KEY (id_guest)
);

CREATE TABLE hook (
  id_hook serial NOT NULL,
  name varchar(64) NOT NULL,
  title varchar(64) NOT NULL,
  description text,
  position int NOT NULL default '1',
  PRIMARY KEY (id_hook),
  UNIQUE (name)
);

CREATE TABLE hook_module (
  id_module int NOT NULL,
  id_hook int NOT NULL,
  position int NOT NULL,
  PRIMARY KEY (id_module,id_hook)
);

CREATE TABLE hook_module_exceptions (
  id_hook_module_exceptions serial NOT NULL,
  id_module int NOT NULL,
  id_hook int NOT NULL,
  file_name varchar(255) default NULL,
  PRIMARY KEY (id_hook_module_exceptions)
);

CREATE TABLE image (
  id_image serial NOT NULL,
  id_product int NOT NULL,
  position int NOT NULL default '0',
  cover int NOT NULL default '0',
  PRIMARY KEY (id_image)
);

CREATE TABLE image_lang (
  id_image int NOT NULL,
  id_lang int NOT NULL,
  legend varchar(128) default NULL
);

CREATE TABLE image_type (
  id_image_type serial NOT NULL,
  name varchar(16) NOT NULL,
  width int NOT NULL,
  height int NOT NULL,
  products int NOT NULL default '1',
  categories int NOT NULL default '1',
  manufacturers int NOT NULL default '1',
  suppliers int NOT NULL default '1',
  scenes int NOT NULL default '1',
  PRIMARY KEY (id_image_type),
  UNIQUE (name)
);

CREATE TABLE lang (
  id_lang serial NOT NULL,
  name varchar(32) NOT NULL,
  active int NOT NULL default '0',
  iso_code char(2) NOT NULL,
  PRIMARY KEY (id_lang),
  UNIQUE (iso_code)
);

CREATE TABLE manufacturer (
  id_manufacturer serial NOT NULL,
  name varchar(64) NOT NULL,
  date_add timestamp NOT NULL,
  date_upd timestamp NOT NULL,
  PRIMARY KEY (id_manufacturer)
);

CREATE TABLE manufacturer_lang (
  id_manufacturer int NOT NULL,
  id_lang int NOT NULL,
  description text,
  short_description varchar(254) default NULL,
  meta_title varchar(254) default NULL,
  meta_keywords varchar(254) default NULL,
  meta_description varchar(254) default NULL,
  PRIMARY KEY (id_manufacturer,id_lang)
);

CREATE TABLE message (
  id_message serial NOT NULL,
  id_cart int default NULL,
  id_customer int NOT NULL,
  id_employee int default NULL,
  id_order int NOT NULL,
  message text NOT NULL,
  private int NOT NULL default '1',
  date_add timestamp NOT NULL,
  PRIMARY KEY (id_message)
);

CREATE TABLE message_readed (
  id_message int NOT NULL,
  id_employee int NOT NULL,
  date_add timestamp NOT NULL,
  PRIMARY KEY (id_message,id_employee)
);

CREATE TABLE meta (
  id_meta serial NOT NULL,
  page varchar(64) NOT NULL,
  PRIMARY KEY (id_meta),
  UNIQUE (page)
);

CREATE TABLE meta_lang (
  id_meta int NOT NULL,
  id_lang int NOT NULL,
  title varchar(255) default NULL,
  description varchar(255) default NULL,
  keywords varchar(255) default NULL,
  PRIMARY KEY (id_meta,id_lang)
);

CREATE TABLE module (
  id_module serial NOT NULL,
  name varchar(64) NOT NULL,
  active int NOT NULL default '0',
  PRIMARY KEY (id_module),
  UNIQUE (name)
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
  name varchar(64) default NULL,
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
  secure_key varchar(32) NOT NULL default '-1',
  payment varchar(255) NOT NULL,
  module varchar(255) default NULL,
  recyclable int NOT NULL default '0',
  gift int NOT NULL default '0',
  gift_message text,
  shipping_number varchar(32) default NULL,
  total_discounts decimal(10,2) NOT NULL default '0.00',
  total_paid decimal(10,2) NOT NULL default '0.00',
  total_paid_real decimal(10,2) NOT NULL default '0.00',
  total_products decimal(10,2) NOT NULL default '0.00',
  total_shipping decimal(10,2) NOT NULL default '0.00',
  total_wrapping decimal(10,2) NOT NULL default '0.00',
  invoice_number int NOT NULL default '0',
  delivery_number int NOT NULL default '0',
  invoice_date timestamp NOT NULL,
  delivery_date timestamp NOT NULL,
  valid int NOT NULL default '0',
  date_add timestamp NOT NULL,
  date_upd timestamp NOT NULL,
  PRIMARY KEY (id_order)
);

CREATE TABLE order_detail (
  id_order_detail serial NOT NULL,
  id_order int NOT NULL,
  product_id int NOT NULL,
  product_attribute_id int default NULL,
  product_name varchar(255) NOT NULL,
  product_quantity int NOT NULL default '0',
  product_quantity_in_stock int NOT NULL default 0,
  product_quantity_refunded int NOT NULL default '0',
  product_quantity_return int NOT NULL default '0',
  product_quantity_reinjected int NOT NULL default 0,
  product_price decimal(13,6) NOT NULL default '0.000000',
  product_quantity_discount decimal(13,6) NOT NULL default '0.000000',
  product_ean13 varchar(13) default NULL,
  product_reference varchar(32) default NULL,
  product_supplier_reference varchar(32) default NULL,
  product_weight float NOT NULL,
  tax_name varchar(16) NOT NULL,
  tax_rate decimal(10,2) NOT NULL default '0.00',
  ecotax decimal(10,2) NOT NULL default '0.00',
  download_hash varchar(255) default NULL,
  download_nb int default '0',
  download_deadline timestamp,
  PRIMARY KEY (id_order_detail),
  UNIQUE (product_id)
);

CREATE TABLE order_discount (
  id_order_discount serial NOT NULL,
  id_order int NOT NULL,
  id_discount int NOT NULL,
  name varchar(32) NOT NULL,
  value decimal(10,2) NOT NULL default '0.00',
  PRIMARY KEY (id_order_discount)
);

CREATE TABLE order_history (
  id_order_history serial NOT NULL,
  id_employee int NOT NULL,
  id_order int NOT NULL,
  id_order_state int NOT NULL,
  date_add timestamp NOT NULL,
  PRIMARY KEY (id_order_history)
);

CREATE TABLE order_message (
  id_order_message serial NOT NULL,
  date_add timestamp NOT NULL,
  PRIMARY KEY (id_order_message)
);

CREATE TABLE order_message_lang (
  id_order_message int NOT NULL,
  id_lang int NOT NULL,
  name varchar(128) NOT NULL,
  message text NOT NULL,
  PRIMARY KEY (id_order_message,id_lang)
);

CREATE TABLE order_return (
  id_order_return serial NOT NULL,
  id_customer int NOT NULL,
  id_order int NOT NULL,
  state int NOT NULL default '1',
  question text NOT NULL,
  date_add timestamp NOT NULL,
  date_upd timestamp NOT NULL,
  PRIMARY KEY (id_order_return)
);

CREATE TABLE order_return_detail (
  id_order_return int NOT NULL,
  id_order_detail int NOT NULL,
  id_customization int NOT NULL default '0',
  product_quantity int NOT NULL default '0',
  PRIMARY KEY (id_order_return,id_order_detail,id_customization)
);

CREATE TABLE order_return_state (
  id_order_return_state serial NOT NULL,
  color varchar(32) default NULL,
  PRIMARY KEY (id_order_return_state)
);

CREATE TABLE order_return_state_lang (
  id_order_return_state int NOT NULL,
  id_lang int NOT NULL,
  name varchar(64) NOT NULL
);

CREATE TABLE order_slip (
  id_order_slip serial NOT NULL,
  id_customer int NOT NULL,
  id_order int NOT NULL,
  shipping_cost int NOT NULL default '0',
  date_add timestamp NOT NULL,
  date_upd timestamp NOT NULL,
  PRIMARY KEY (id_order_slip)
);

CREATE TABLE order_slip_detail (
  id_order_slip int NOT NULL,
  id_order_detail int NOT NULL,
  product_quantity int NOT NULL default '0',
  PRIMARY KEY (id_order_slip,id_order_detail)
);

CREATE TABLE order_state (
  id_order_state serial NOT NULL,
  invoice int default '0',
  send_email int NOT NULL default '0',
  color varchar(32) default NULL,
  unremovable int NOT NULL,
  hidden int NOT NULL default '0',
  logable int NOT NULL default '0',
  delivery int NOT NULL default '0',
  PRIMARY KEY (id_order_state)
);

CREATE TABLE order_state_lang (
  id_order_state int NOT NULL,
  id_lang int NOT NULL,
  name varchar(64) NOT NULL,
  template varchar(64) NOT NULL
);

CREATE TABLE pack (
  id_product_pack int NOT NULL,
  id_product_item int NOT NULL,
  quantity int NOT NULL DEFAULT 1,
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
  name varchar(255) NOT NULL,
  PRIMARY KEY (id_page_type),
  UNIQUE (name)
);

CREATE TABLE page_viewed (
  id_page int NOT NULL,
  id_date_range int NOT NULL,
  counter int NOT NULL,
  PRIMARY KEY (id_page,id_date_range)
);

CREATE TABLE product (
  type varchar(32) default NULL,
  id_product serial NOT NULL,
  id_supplier int default NULL,
  id_manufacturer int default NULL,
  id_tax int NOT NULL,
  id_category_default int default NULL,
  id_color_default int default NULL,
  on_sale int NOT NULL default '0',
  ean13 varchar(13) default NULL,
  ecotax decimal(10,2) NOT NULL default '0.00',
  quantity int NOT NULL default '0',
  price decimal(13,6) NOT NULL default '0.000000',
  wholesale_price decimal(13,6) NOT NULL default '0.000000',
  reduction_price decimal(10,2) default NULL,
  reduction_percent float default NULL,
  reduction_from date default NULL,
  reduction_to date default NULL,
  reference varchar(32) default NULL,
  supplier_reference varchar(32) default NULL,
  location varchar(64) default NULL,
  weight float NOT NULL default '0',
  out_of_stock int NOT NULL default '2',
  quantity_discount int default '0',
  customizable int NOT NULL default '0',
  uploadable_files int NOT NULL default '0',
  text_fields int NOT NULL default '0',
  active int NOT NULL default '0',
  indexed int NOT NULL default '0',
  date_add timestamp NOT NULL,
  date_upd timestamp NOT NULL,
  PRIMARY KEY (id_product)
);

CREATE TABLE product_attribute (
  id_product_attribute serial NOT NULL,
  id_product int NOT NULL,
  reference varchar(32) default NULL,
  supplier_reference varchar(32) default NULL,
  location varchar(64) default NULL,
  ean13 varchar(13) default NULL,
  wholesale_price decimal(13,6) NOT NULL default '0.000000',
  price decimal(10,2) NOT NULL default '0.00',
  ecotax decimal(10,2) NOT NULL default '0.00',
  quantity int NOT NULL default '0',
  weight float NOT NULL default '0',
  default_on int NOT NULL default '0',
  PRIMARY KEY (id_product_attribute),
  UNIQUE (reference),
  UNIQUE (supplier_reference)
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
  display_filename varchar(255) default NULL,
  physically_filename varchar(255) default NULL,
  date_deposit timestamp NOT NULL,
  date_expiration timestamp default NULL,
  nb_days_accessible int default NULL,
  nb_downloadable int default '1',
  active int NOT NULL default '1',
  PRIMARY KEY (id_product_download)
);

CREATE TABLE product_lang (
  id_product int NOT NULL,
  id_lang int NOT NULL,
  description text,
  description_short text,
  link_rewrite varchar(128) NOT NULL,
  meta_description varchar(255) default NULL,
  meta_keywords varchar(255) default NULL,
  meta_title varchar(128) default NULL,
  name varchar(128) NOT NULL,
  available_now varchar(255) default NULL,
  available_later varchar(255) default NULL,
  UNIQUE (name)
);

CREATE TABLE product_sale (
  id_product int NOT NULL,
  quantity int NOT NULL default '0',
  sale_nbr int NOT NULL default '0',
  date_upd date NOT NULL,
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
  name varchar(128) NOT NULL,
  PRIMARY KEY (id_profile,id_lang)
);

CREATE TABLE quick_access (
  id_quick_access serial NOT NULL,
  new_window int NOT NULL default '0',
  link varchar(128) NOT NULL,
  PRIMARY KEY (id_quick_access)
);

CREATE TABLE quick_access_lang (
  id_quick_access int NOT NULL,
  id_lang int NOT NULL,
  name varchar(32) NOT NULL,
  PRIMARY KEY (id_quick_access,id_lang)
);

CREATE TABLE range_price (
  id_range_price serial NOT NULL,
  id_carrier int NOT NULL,
  delimiter1 decimal(13,6) NOT NULL,
  delimiter2 decimal(13,6) NOT NULL,
  PRIMARY KEY (id_range_price),
  UNIQUE (id_carrier,delimiter1,delimiter2)
);

CREATE TABLE range_weight (
  id_range_weight serial NOT NULL,
  id_carrier int NOT NULL,
  delimiter1 decimal(13,6) NOT NULL,
  delimiter2 decimal(13,6) NOT NULL,
  PRIMARY KEY (id_range_weight),
  UNIQUE (id_carrier,delimiter1,delimiter2)
);

CREATE TABLE referrer (
  id_referrer serial NOT NULL,
  name varchar(64) NOT NULL,
  passwd varchar(32) default NULL,
  http_referer_regexp varchar(64) default NULL,
  http_referer_like varchar(64) default NULL,
  request_uri_regexp varchar(64) default NULL,
  request_uri_like varchar(64) default NULL,
  http_referer_regexp_not varchar(64) default NULL,
  http_referer_like_not varchar(64) default NULL,
  request_uri_regexp_not varchar(64) default NULL,
  request_uri_like_not varchar(64) default NULL,
  base_fee decimal(5,2) NOT NULL default '0.00',
  percent_fee decimal(5,2) NOT NULL default '0.00',
  click_fee decimal(5,2) NOT NULL default '0.00',
  cache_visitors int default NULL,
  cache_visits int default NULL,
  cache_pages int default NULL,
  cache_registrations int default NULL,
  cache_orders int default NULL,
  cache_sales decimal(10,2) default NULL,
  cache_reg_rate decimal(5,4) default NULL,
  cache_order_rate decimal(5,4) default NULL,
  date_add timestamp NOT NULL,
  PRIMARY KEY (id_referrer)
);

CREATE TABLE referrer_cache (
  id_connections_source int NOT NULL,
  id_referrer int NOT NULL,
  PRIMARY KEY (id_connections_source, id_referrer)
);

CREATE TABLE scene (
  id_scene serial NOT NULL,
  active int NOT NULL default '1',
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
  name varchar(100) NOT NULL,
  PRIMARY KEY (id_scene,id_lang)
);

CREATE TABLE scene_products (
  id_scene int NOT NULL,
  id_product int NOT NULL,
  x_axis int NOT NULL,
  y_axis int NOT NULL,
  zone_width int NOT NULL,
  zone_height int NOT NULL,
  PRIMARY KEY (id_scene, id_product, x_axis, y_axis)
);

CREATE TABLE search_engine (
  id_search_engine serial NOT NULL,
  server varchar(64) NOT NULL,
  getvar varchar(16) NOT NULL,
  PRIMARY KEY (id_search_engine)
);

CREATE TABLE search_index (
  id_product int NOT NULL,
  id_word int NOT NULL,
  weight int NOT NULL default '1',
  PRIMARY KEY (id_word, id_product)
);

CREATE TABLE search_word (
  id_word serial NOT NULL,
  id_lang int NOT NULL,
  word varchar(15) NOT NULL,
  PRIMARY KEY (id_word),
  UNIQUE (word)
);

CREATE TABLE state (
  id_state serial NOT NULL,
  id_country int NOT NULL,
  id_zone int NOT NULL,
  name varchar(64) NOT NULL,
  iso_code char(4) NOT NULL,
  tax_behavior smallint NOT NULL default '0',
  active int NOT NULL default '0',
  PRIMARY KEY (id_state)
);

CREATE TABLE subdomain (
  id_subdomain serial NOT NULL,
  name varchar(16) NOT NULL,
  PRIMARY KEY (id_subdomain)
);

CREATE TABLE supplier (
  id_supplier serial NOT NULL,
  name varchar(64) NOT NULL,
  date_add timestamp NOT NULL,
  date_upd timestamp NOT NULL,
  PRIMARY KEY (id_supplier)
);

CREATE TABLE supplier_lang (
  id_supplier int NOT NULL,
  id_lang int NOT NULL,
  description text,
  meta_title varchar(254) default NULL,
  meta_keywords varchar(254) default NULL,
  meta_description varchar(254) default NULL,
  PRIMARY KEY (id_supplier,id_lang)
);

CREATE TABLE tab (
  id_tab serial NOT NULL,
  id_parent int NOT NULL,
  class_name varchar(64) NOT NULL,
  module varchar(64) NULL,
  position int NOT NULL,
  PRIMARY KEY (id_tab)
);

CREATE TABLE tab_lang (
  id_lang int NOT NULL,
  id_tab int NOT NULL,
  name varchar(32) default NULL,
  PRIMARY KEY (id_tab,id_lang)
);

CREATE TABLE tag (
  id_tag serial NOT NULL,
  id_lang int NOT NULL,
  name varchar(32) NOT NULL,
  PRIMARY KEY (id_tag),
  UNIQUE (name)
);

CREATE TABLE tax (
  id_tax serial NOT NULL,
  rate float NOT NULL,
  PRIMARY KEY (id_tax)
);

CREATE TABLE tax_lang (
  id_tax int NOT NULL,
  id_lang int NOT NULL,
  name varchar(32) NOT NULL
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
  name varchar(64) default NULL,
  PRIMARY KEY (id_web_browser)
);

CREATE TABLE zone (
  id_zone serial NOT NULL,
  name varchar(64) NOT NULL,
  active int NOT NULL default '0',
  enabled int NOT NULL default '0',
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

----

ALTER TABLE feature_lang ADD FOREIGN KEY (id_lang) REFERENCES lang (id_lang);
ALTER TABLE feature_lang ADD FOREIGN KEY (id_feature) REFERENCES feature (id_feature);
ALTER TABLE tag ADD FOREIGN KEY (id_lang) REFERENCES lang (id_lang);
ALTER TABLE tab_lang ADD FOREIGN KEY (id_lang) REFERENCES lang (id_lang);
ALTER TABLE tab_lang ADD FOREIGN KEY (id_tab) REFERENCES tab (id_tab);
ALTER TABLE supplier_lang ADD FOREIGN KEY (id_lang) REFERENCES lang (id_lang);
ALTER TABLE supplier_lang ADD FOREIGN KEY (id_supplier) REFERENCES supplier (id_supplier);
ALTER TABLE scene_lang ADD FOREIGN KEY (id_scene) REFERENCES scene (id_scene);
ALTER TABLE scene_lang ADD FOREIGN KEY (id_lang) REFERENCES lang (id_lang);
ALTER TABLE profile_lang ADD FOREIGN KEY (id_profile) REFERENCES profile (id_profile);
ALTER TABLE profile_lang ADD FOREIGN KEY (id_lang) REFERENCES lang (id_lang);
ALTER TABLE order_message_lang ADD FOREIGN KEY (id_order_message) REFERENCES order_message (id_order_message);
ALTER TABLE order_message_lang ADD FOREIGN KEY (id_lang) REFERENCES lang (id_lang);
ALTER TABLE meta_lang ADD FOREIGN KEY (id_meta) REFERENCES meta (id_meta);
ALTER TABLE meta_lang ADD FOREIGN KEY (id_lang) REFERENCES lang (id_lang);
ALTER TABLE manufacturer_lang ADD FOREIGN KEY (id_manufacturer) REFERENCES manufacturer (id_manufacturer);
ALTER TABLE manufacturer_lang ADD FOREIGN KEY (id_lang) REFERENCES lang (id_lang);
ALTER TABLE feature_value_lang ADD FOREIGN KEY (id_feature_value) REFERENCES feature_value (id_feature_value);
ALTER TABLE feature_value_lang ADD FOREIGN KEY (id_lang) REFERENCES lang (id_lang);
ALTER TABLE discount_type_lang ADD FOREIGN KEY (id_discount_type) REFERENCES discount_type (id_discount_type);
ALTER TABLE discount_type_lang ADD FOREIGN KEY (id_lang) REFERENCES lang (id_lang);
ALTER TABLE discount_lang ADD FOREIGN KEY (id_discount) REFERENCES discount (id_discount);
ALTER TABLE discount_lang ADD FOREIGN KEY (id_lang) REFERENCES lang (id_lang);
ALTER TABLE customization_field_lang ADD FOREIGN KEY (id_customization_field) REFERENCES customization_field (id_customization_field);
ALTER TABLE customization_field_lang ADD FOREIGN KEY (id_lang) REFERENCES lang (id_lang);
ALTER TABLE configuration_lang ADD FOREIGN KEY (id_configuration) REFERENCES configuration (id_configuration);
ALTER TABLE configuration_lang ADD FOREIGN KEY (id_lang) REFERENCES lang (id_lang);
ALTER TABLE cms_lang ADD FOREIGN KEY (id_cms) REFERENCES cms (id_cms);
ALTER TABLE cms_lang ADD FOREIGN KEY (id_lang) REFERENCES lang (id_lang);
ALTER TABLE attribute_group_lang ADD FOREIGN KEY (id_attribute_group) REFERENCES attribute_group (id_attribute_group);
ALTER TABLE attribute_group_lang ADD FOREIGN KEY (id_lang) REFERENCES lang (id_lang);
ALTER TABLE attachment_lang ADD FOREIGN KEY (id_attachment) REFERENCES attachment (id_attachment);
ALTER TABLE attachment_lang ADD FOREIGN KEY (id_lang) REFERENCES lang (id_lang);
ALTER TABLE attribute_lang ADD FOREIGN KEY (id_lang) REFERENCES lang (id_lang);
ALTER TABLE carrier_lang ADD FOREIGN KEY (id_lang) REFERENCES lang (id_lang);
ALTER TABLE carrier_lang ADD FOREIGN KEY (id_carrier) REFERENCES carrier (id_carrier);
ALTER TABLE category_lang ADD FOREIGN KEY (id_category) REFERENCES category (id_category);
ALTER TABLE category_lang ADD FOREIGN KEY (id_lang) REFERENCES lang (id_lang);
ALTER TABLE contact_lang ADD FOREIGN KEY (id_contact) REFERENCES contact (id_contact);
ALTER TABLE contact_lang ADD FOREIGN KEY (id_lang) REFERENCES lang (id_lang);
ALTER TABLE country_lang ADD FOREIGN KEY (id_country) REFERENCES country (id_country);
ALTER TABLE country_lang ADD FOREIGN KEY (id_lang) REFERENCES lang (id_lang);
ALTER TABLE _group_lang ADD FOREIGN KEY (id_group) REFERENCES _group (id_group);
ALTER TABLE _group_lang ADD FOREIGN KEY (id_lang) REFERENCES lang (id_lang);
ALTER TABLE image_lang ADD FOREIGN KEY (id_image) REFERENCES image (id_image);
ALTER TABLE image_lang ADD FOREIGN KEY (id_lang) REFERENCES lang (id_lang);
ALTER TABLE order_return_state_lang ADD FOREIGN KEY (id_order_return_state) REFERENCES order_return_state (id_order_return_state);
ALTER TABLE order_return_state_lang ADD FOREIGN KEY (id_lang) REFERENCES lang (id_lang);
ALTER TABLE order_state_lang ADD FOREIGN KEY (id_order_state) REFERENCES order_state (id_order_state);
ALTER TABLE order_state_lang ADD FOREIGN KEY (id_lang) REFERENCES lang (id_lang);
ALTER TABLE product_lang ADD FOREIGN KEY (id_product) REFERENCES product (id_product);
ALTER TABLE product_lang ADD FOREIGN KEY (id_lang) REFERENCES lang (id_lang);
ALTER TABLE product_lang ADD FOREIGN KEY (id_lang) REFERENCES lang (id_lang);
ALTER TABLE search_word ADD FOREIGN KEY (id_lang) REFERENCES lang (id_lang);
ALTER TABLE tax_lang ADD FOREIGN KEY (id_tax) REFERENCES tax (id_tax);
ALTER TABLE tax_lang ADD FOREIGN KEY (id_lang) REFERENCES lang (id_lang);
