SET NAMES 'utf8';

CREATE TABLE PREFIX_access (
  id_profile int NOT NULL,
  id_tab int NOT NULL,
  view int NOT NULL,
  add int NOT NULL,
  edit int NOT NULL,
  delete int NOT NULL,
  PRIMARY KEY (id_profile,id_tab)
);

CREATE TABLE PREFIX_accessory (
  id_product_1 int NOT NULL,
  id_product_2 int NOT NULL
);

CREATE TABLE PREFIX_address (
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

CREATE TABLE PREFIX_alias (
  id_alias serial NOT NULL,
  alias varchar(255) NOT NULL,
  search varchar(255) NOT NULL,
  active int NOT NULL default '1',
  PRIMARY KEY (id_alias),
  UNIQUE (alias)
);

CREATE TABLE PREFIX_attachment (
  id_attachment serial NOT NULL,
  file varchar(40) NOT NULL,
  mime varchar(32) NOT NULL,
  PRIMARY KEY (id_attachment)
);

CREATE TABLE PREFIX_attachment_lang (
  id_attachment serial NOT NULL,
  id_lang int NOT NULL,
  name varchar(32) default NULL,
  description TEXT,
  PRIMARY KEY (id_attachment, id_lang)
);

CREATE TABLE PREFIX_product_attachment (
  id_product int NOT NULL,
  id_attachment int NOT NULL,
  PRIMARY KEY (id_product,id_attachment)
);

CREATE TABLE PREFIX_attribute (
  id_attribute serial NOT NULL,
  id_attribute_group int NOT NULL,
  color varchar(32) default NULL,
  PRIMARY KEY (id_attribute)
);

CREATE TABLE PREFIX_attribute_group (
  id_attribute_group serial NOT NULL,
  is_color_group int NOT NULL default '0',
  PRIMARY KEY (id_attribute_group)
);

CREATE TABLE PREFIX_attribute_group_lang (
  id_attribute_group int NOT NULL,
  id_lang int NOT NULL,
  name varchar(128) NOT NULL,
  public_name varchar(64) NOT NULL,
  PRIMARY KEY (id_attribute_group,id_lang)
);

CREATE TABLE PREFIX_attribute_impact (
  id_attribute_impact serial NOT NULL,
  id_product int NOT NULL,
  id_attribute int NOT NULL,
  weight float NOT NULL,
  price decimal(10,2) NOT NULL,
  PRIMARY KEY (id_attribute_impact)
);

CREATE TABLE PREFIX_attribute_lang (
  id_attribute int NOT NULL,
  id_lang int NOT NULL,
  name varchar(128) NOT NULL,
  PRIMARY KEY (id_attribute,id_lang),
  UNIQUE (name)
);

CREATE TABLE PREFIX_block_cms (
  id_block int NOT NULL,
  id_cms int NOT NULL,
  PRIMARY KEY (id_block,id_cms)
);

CREATE TABLE PREFIX_carrier (
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

CREATE TABLE PREFIX_carrier_lang (
  id_carrier int NOT NULL,
  id_lang int NOT NULL,
  delay varchar(128) default NULL
);

CREATE TABLE PREFIX_carrier_zone (
  id_carrier int NOT NULL,
  id_zone int NOT NULL,
  PRIMARY KEY (id_carrier,id_zone)
);

CREATE TABLE PREFIX_cart (
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

CREATE TABLE PREFIX_cart_discount (
  id_cart int NOT NULL,
  id_discount int NOT NULL
);

CREATE TABLE PREFIX_cart_product (
  id_cart int NOT NULL,
  id_product int NOT NULL,
  id_product_attribute int default NULL,
  quantity int NOT NULL default '0',
  date_add timestamp NOT NULL
);

CREATE TABLE PREFIX_category (
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

CREATE TABLE PREFIX_category_group (
  id_category int NOT NULL,
  id_group int NOT NULL
);

CREATE TABLE PREFIX_category_lang (
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

CREATE TABLE PREFIX_category_product (
  id_category int NOT NULL,
  id_product int NOT NULL,
  position int NOT NULL default '0'
);

CREATE TABLE PREFIX_cms (
  id_cms serial NOT NULL,
  PRIMARY KEY (id_cms)
);

CREATE TABLE PREFIX_cms_lang (
  id_cms serial NOT NULL,
  id_lang int NOT NULL,
  meta_title varchar(128) NOT NULL,
  meta_description varchar(255) default NULL,
  meta_keywords varchar(255) default NULL,
  content varchar,
  link_rewrite varchar(128) NOT NULL,
  PRIMARY KEY (id_cms,id_lang)
);

CREATE TABLE PREFIX_configuration (
  id_configuration serial NOT NULL,
  name varchar(32) NOT NULL,
  value text,
  date_add timestamp NOT NULL,
  date_upd timestamp NOT NULL,
  PRIMARY KEY (id_configuration),
  UNIQUE (name)
);

CREATE TABLE PREFIX_configuration_lang (
  id_configuration int NOT NULL,
  id_lang int NOT NULL,
  value text,
  date_upd timestamp default NULL,
  PRIMARY KEY (id_configuration,id_lang)
);

CREATE TABLE PREFIX_connections (
  id_connections serial NOT NULL,
  id_guest int NOT NULL,
  id_page int NOT NULL,
  ip_address varchar(16) default NULL,
  date_add timestamp NOT NULL,
  http_referer varchar(255) default NULL,
  PRIMARY KEY (id_connections),
  UNIQUE (date_add)
);

CREATE TABLE PREFIX_connections_page (
  id_connections int NOT NULL,
  id_page int NOT NULL,
  time_start timestamp NOT NULL,
  time_end timestamp default NULL,
  PRIMARY KEY (id_connections,id_page,time_start)
);

CREATE TABLE PREFIX_connections_source (
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

CREATE TABLE PREFIX_contact (
  id_contact serial NOT NULL,
  email varchar(128) NOT NULL,
  position int NOT NULL default '0',
  PRIMARY KEY (id_contact)
);

CREATE TABLE PREFIX_contact_lang (
  id_contact int NOT NULL,
  id_lang int NOT NULL,
  name varchar(32) NOT NULL,
  description text
);

CREATE TABLE PREFIX_country (
  id_country serial NOT NULL,
  id_zone int NOT NULL,
  iso_code varchar(3) NOT NULL,
  active int NOT NULL default '0',
  contains_states int NOT NULL default '0',
  PRIMARY KEY (id_country),
  UNIQUE (iso_code)
);

CREATE TABLE PREFIX_country_lang (
  id_country int NOT NULL,
  id_lang int NOT NULL,
  name varchar(64) NOT NULL
);

CREATE TABLE PREFIX_currency (
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

CREATE TABLE PREFIX_customer (
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

CREATE TABLE PREFIX_customer_group (
  id_customer int NOT NULL,
  id_group int NOT NULL,
  PRIMARY KEY (id_customer,id_group)
);

CREATE TABLE PREFIX_customization (
  id_customization serial NOT NULL,
  id_product_attribute int NOT NULL default '0',
  id_cart int NOT NULL,
  id_product int NOT NULL,
  quantity int NOT NULL,
  quantity_refunded INT NOT NULL DEFAULT '0',
  quantity_returned INT NOT NULL DEFAULT '0',
  PRIMARY KEY (id_customization,id_cart,id_product)
);

CREATE TABLE PREFIX_customization_field (
  id_customization_field serial NOT NULL,
  id_product int NOT NULL,
  type int NOT NULL,
  required int NOT NULL,
  PRIMARY KEY (id_customization_field)
);

CREATE TABLE PREFIX_customization_field_lang (
  id_customization_field int NOT NULL,
  id_lang int NOT NULL,
  name varchar(255) NOT NULL,
  PRIMARY KEY (id_customization_field,id_lang)
);

CREATE TABLE PREFIX_customized_data (
  id_customization int NOT NULL,
  type int NOT NULL,
  index int NOT NULL,
  value varchar(255) NOT NULL,
  PRIMARY KEY (id_customization,type,index)
);

CREATE TABLE PREFIX_date_range (
  id_date_range serial NOT NULL,
  time_start timestamp NOT NULL,
  time_end timestamp NOT NULL,
  PRIMARY KEY (id_date_range)
);

CREATE TABLE PREFIX_delivery (
  id_delivery serial NOT NULL,
  id_carrier int NOT NULL,
  id_range_price int default NULL,
  id_range_weight int default NULL,
  id_zone int NOT NULL,
  price decimal(10,2) NOT NULL,
  PRIMARY KEY (id_delivery)
);

CREATE TABLE PREFIX_discount (
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

CREATE TABLE PREFIX_discount_category (
  id_category int NOT NULL,
  id_discount int NOT NULL,
  PRIMARY KEY (id_category, id_discount)
);

CREATE TABLE PREFIX_discount_lang (
  id_discount int NOT NULL,
  id_lang int NOT NULL,
  description text,
  PRIMARY KEY (id_discount,id_lang)
);

CREATE TABLE PREFIX_discount_quantity (
  id_discount_quantity serial NOT NULL,
  id_discount_type int NOT NULL,
  id_product int NOT NULL,
  id_product_attribute int default NULL,
  quantity int NOT NULL,
  value decimal(10,2) NOT NULL,
  PRIMARY KEY (id_discount_quantity)
);

CREATE TABLE PREFIX_discount_type (
  id_discount_type serial NOT NULL,
  PRIMARY KEY (id_discount_type)
);

CREATE TABLE PREFIX_discount_type_lang (
  id_discount_type int NOT NULL,
  id_lang int NOT NULL,
  name varchar(64) NOT NULL,
  PRIMARY KEY (id_discount_type,id_lang)
);

CREATE TABLE PREFIX_employee (
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

CREATE TABLE PREFIX_feature (
  id_feature serial NOT NULL,
  PRIMARY KEY (id_feature)
);

CREATE TABLE PREFIX_feature_lang (
  id_feature int NOT NULL,
  id_lang int NOT NULL,
  name varchar(128) default NULL,
  PRIMARY KEY (id_feature,id_lang)
);

CREATE TABLE PREFIX_feature_product (
  id_feature int NOT NULL,
  id_product int NOT NULL,
  id_feature_value int NOT NULL,
  PRIMARY KEY (id_feature,id_product)
);

CREATE TABLE PREFIX_feature_value (
  id_feature_value serial NOT NULL,
  id_feature int NOT NULL,
  custom int default NULL,
  PRIMARY KEY (id_feature_value)
);

CREATE TABLE PREFIX_feature_value_lang (
  id_feature_value int NOT NULL,
  id_lang int NOT NULL,
  value varchar(255) default NULL,
  PRIMARY KEY (id_feature_value,id_lang)
);

CREATE TABLE PREFIX_group (
  id_group serial NOT NULL,
  reduction decimal(10,2) NOT NULL default '0.00',
  date_add timestamp NOT NULL,
  date_upd timestamp NOT NULL,
  PRIMARY KEY (id_group)
);

CREATE TABLE PREFIX_group_lang (
  id_group int NOT NULL,
  id_lang int NOT NULL,
  name varchar(32) NOT NULL
);

CREATE TABLE PREFIX_guest (
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

CREATE TABLE PREFIX_hook (
  id_hook serial NOT NULL,
  name varchar(64) NOT NULL,
  title varchar(64) NOT NULL,
  description text,
  position int NOT NULL default '1',
  PRIMARY KEY (id_hook),
  UNIQUE (name)
);

CREATE TABLE PREFIX_hook_module (
  id_module int NOT NULL,
  id_hook int NOT NULL,
  position int NOT NULL,
  PRIMARY KEY (id_module,id_hook)
);

CREATE TABLE PREFIX_hook_module_exceptions (
  id_hook_module_exceptions serial NOT NULL,
  id_module int NOT NULL,
  id_hook int NOT NULL,
  file_name varchar(255) default NULL,
  PRIMARY KEY (id_hook_module_exceptions)
);

CREATE TABLE PREFIX_image (
  id_image serial NOT NULL,
  id_product int NOT NULL,
  position int NOT NULL default '0',
  cover int NOT NULL default '0',
  PRIMARY KEY (id_image)
);

CREATE TABLE PREFIX_image_lang (
  id_image int NOT NULL,
  id_lang int NOT NULL,
  legend varchar(128) default NULL
);

CREATE TABLE PREFIX_image_type (
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

CREATE TABLE PREFIX_lang (
  id_lang serial NOT NULL,
  name varchar(32) NOT NULL,
  active int NOT NULL default '0',
  iso_code char(2) NOT NULL,
  PRIMARY KEY (id_lang),
  UNIQUE (iso_code)
);

CREATE TABLE PREFIX_manufacturer (
  id_manufacturer serial NOT NULL,
  name varchar(64) NOT NULL,
  date_add timestamp NOT NULL,
  date_upd timestamp NOT NULL,
  PRIMARY KEY (id_manufacturer)
);

CREATE TABLE PREFIX_manufacturer_lang (
  id_manufacturer int NOT NULL,
  id_lang int NOT NULL,
  description text,
  short_description varchar(254) default NULL,
  meta_title varchar(254) default NULL,
  meta_keywords varchar(254) default NULL,
  meta_description varchar(254) default NULL,
  PRIMARY KEY (id_manufacturer,id_lang)
);

CREATE TABLE PREFIX_message (
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

CREATE TABLE PREFIX_message_readed (
  id_message int NOT NULL,
  id_employee int NOT NULL,
  date_add timestamp NOT NULL,
  PRIMARY KEY (id_message,id_employee)
);

CREATE TABLE PREFIX_meta (
  id_meta serial NOT NULL,
  page varchar(64) NOT NULL,
  PRIMARY KEY (id_meta),
  UNIQUE (page)
);

CREATE TABLE PREFIX_meta_lang (
  id_meta int NOT NULL,
  id_lang int NOT NULL,
  title varchar(255) default NULL,
  description varchar(255) default NULL,
  keywords varchar(255) default NULL,
  PRIMARY KEY (id_meta,id_lang)
);

CREATE TABLE PREFIX_module (
  id_module serial NOT NULL,
  name varchar(64) NOT NULL,
  active int NOT NULL default '0',
  PRIMARY KEY (id_module),
  UNIQUE (name)
);

CREATE TABLE PREFIX_module_country (
  id_module int NOT NULL,
  id_country int NOT NULL,
  PRIMARY KEY (id_module,id_country)
);

CREATE TABLE PREFIX_module_currency (
  id_module int NOT NULL,
  id_currency int NOT NULL,
  PRIMARY KEY (id_module,id_currency)
);

CREATE TABLE PREFIX_module_group (
  id_module int NOT NULL,
  id_group int NOT NULL,
  PRIMARY KEY (id_module,id_group)
);

CREATE TABLE PREFIX_operating_system (
  id_operating_system serial NOT NULL,
  name varchar(64) default NULL,
  PRIMARY KEY (id_operating_system)
);

CREATE TABLE PREFIX_orders (
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

CREATE TABLE PREFIX_order_detail (
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

CREATE TABLE PREFIX_order_discount (
  id_order_discount serial NOT NULL,
  id_order int NOT NULL,
  id_discount int NOT NULL,
  name varchar(32) NOT NULL,
  value decimal(10,2) NOT NULL default '0.00',
  PRIMARY KEY (id_order_discount)
);

CREATE TABLE PREFIX_order_history (
  id_order_history serial NOT NULL,
  id_employee int NOT NULL,
  id_order int NOT NULL,
  id_order_state int NOT NULL,
  date_add timestamp NOT NULL,
  PRIMARY KEY (id_order_history)
);

CREATE TABLE PREFIX_order_message (
  id_order_message serial NOT NULL,
  date_add timestamp NOT NULL,
  PRIMARY KEY (id_order_message)
);

CREATE TABLE PREFIX_order_message_lang (
  id_order_message int NOT NULL,
  id_lang int NOT NULL,
  name varchar(128) NOT NULL,
  message text NOT NULL,
  PRIMARY KEY (id_order_message,id_lang)
);

CREATE TABLE PREFIX_order_return (
  id_order_return serial NOT NULL,
  id_customer int NOT NULL,
  id_order int NOT NULL,
  state int NOT NULL default '1',
  question text NOT NULL,
  date_add timestamp NOT NULL,
  date_upd timestamp NOT NULL,
  PRIMARY KEY (id_order_return)
);

CREATE TABLE PREFIX_order_return_detail (
  id_order_return int NOT NULL,
  id_order_detail int NOT NULL,
  id_customization int NOT NULL default '0',
  product_quantity int NOT NULL default '0',
  PRIMARY KEY (id_order_return,id_order_detail,id_customization)
);

CREATE TABLE PREFIX_order_return_state (
  id_order_return_state serial NOT NULL,
  color varchar(32) default NULL,
  PRIMARY KEY (id_order_return_state)
);

CREATE TABLE PREFIX_order_return_state_lang (
  id_order_return_state int NOT NULL,
  id_lang int NOT NULL,
  name varchar(64) NOT NULL
);

CREATE TABLE PREFIX_order_slip (
  id_order_slip serial NOT NULL,
  id_customer int NOT NULL,
  id_order int NOT NULL,
  shipping_cost int NOT NULL default '0',
  date_add timestamp NOT NULL,
  date_upd timestamp NOT NULL,
  PRIMARY KEY (id_order_slip)
);

CREATE TABLE PREFIX_order_slip_detail (
  id_order_slip int NOT NULL,
  id_order_detail int NOT NULL,
  product_quantity int NOT NULL default '0',
  PRIMARY KEY (id_order_slip,id_order_detail)
);

CREATE TABLE PREFIX_order_state (
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

CREATE TABLE PREFIX_order_state_lang (
  id_order_state int NOT NULL,
  id_lang int NOT NULL,
  name varchar(64) NOT NULL,
  template varchar(64) NOT NULL
);

CREATE TABLE PREFIX_pack (
  id_product_pack int NOT NULL,
  id_product_item int NOT NULL,
  quantity int NOT NULL DEFAULT 1,
  PRIMARY KEY (id_product_pack,id_product_item)
);

CREATE TABLE PREFIX_page (
  id_page serial NOT NULL,
  id_page_type int NOT NULL,
  id_object int default NULL,
  PRIMARY KEY (id_page)
);

CREATE TABLE PREFIX_page_type (
  id_page_type serial NOT NULL,
  name varchar(255) NOT NULL,
  PRIMARY KEY (id_page_type),
  UNIQUE (name)
);

CREATE TABLE PREFIX_page_viewed (
  id_page int NOT NULL,
  id_date_range int NOT NULL,
  counter int NOT NULL,
  PRIMARY KEY (id_page,id_date_range)
);

CREATE TABLE PREFIX_product (
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

CREATE TABLE PREFIX_product_attribute (
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

CREATE TABLE PREFIX_product_attribute_combination (
  id_attribute int NOT NULL,
  id_product_attribute int NOT NULL,
  PRIMARY KEY (id_attribute,id_product_attribute)
);

CREATE TABLE PREFIX_product_attribute_image (
  id_product_attribute int NOT NULL,
  id_image int NOT NULL,
  PRIMARY KEY (id_product_attribute,id_image)
);

CREATE TABLE PREFIX_product_download (
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

CREATE TABLE PREFIX_product_lang (
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

CREATE TABLE PREFIX_product_sale (
  id_product int NOT NULL,
  quantity int NOT NULL default '0',
  sale_nbr int NOT NULL default '0',
  date_upd date NOT NULL,
  PRIMARY KEY (id_product)
);

CREATE TABLE PREFIX_product_tag (
  id_product int NOT NULL,
  id_tag int NOT NULL,
  PRIMARY KEY (id_product,id_tag)
);

CREATE TABLE PREFIX_profile (
  id_profile serial NOT NULL,
  PRIMARY KEY (id_profile)
);

CREATE TABLE PREFIX_profile_lang (
  id_lang int NOT NULL,
  id_profile int NOT NULL,
  name varchar(128) NOT NULL,
  PRIMARY KEY (id_profile,id_lang)
);

CREATE TABLE PREFIX_quick_access (
  id_quick_access serial NOT NULL,
  new_window int NOT NULL default '0',
  link varchar(128) NOT NULL,
  PRIMARY KEY (id_quick_access)
);

CREATE TABLE PREFIX_quick_access_lang (
  id_quick_access int NOT NULL,
  id_lang int NOT NULL,
  name varchar(32) NOT NULL,
  PRIMARY KEY (id_quick_access,id_lang)
);

CREATE TABLE PREFIX_range_price (
  id_range_price serial NOT NULL,
  id_carrier int NOT NULL,
  delimiter1 decimal(13,6) NOT NULL,
  delimiter2 decimal(13,6) NOT NULL,
  PRIMARY KEY (id_range_price),
  UNIQUE (id_carrier,delimiter1,delimiter2)
);

CREATE TABLE PREFIX_range_weight (
  id_range_weight serial NOT NULL,
  id_carrier int NOT NULL,
  delimiter1 decimal(13,6) NOT NULL,
  delimiter2 decimal(13,6) NOT NULL,
  PRIMARY KEY (id_range_weight),
  UNIQUE (id_carrier,delimiter1,delimiter2)
);

CREATE TABLE PREFIX_referrer (
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

CREATE TABLE PREFIX_referrer_cache (
  id_connections_source int NOT NULL,
  id_referrer int NOT NULL,
  PRIMARY KEY (id_connections_source, id_referrer)
);

CREATE TABLE PREFIX_scene (
  id_scene serial NOT NULL,
  active int NOT NULL default '1',
  PRIMARY KEY (id_scene)
);

CREATE TABLE PREFIX_scene_category (
  id_scene int NOT NULL,
  id_category int NOT NULL,
  PRIMARY KEY (id_scene,id_category)
);

CREATE TABLE PREFIX_scene_lang (
  id_scene int NOT NULL,
  id_lang int NOT NULL,
  name varchar(100) NOT NULL,
  PRIMARY KEY (id_scene,id_lang)
);

CREATE TABLE PREFIX_scene_products (
  id_scene int NOT NULL,
  id_product int NOT NULL,
  x_axis int NOT NULL,
  y_axis int NOT NULL,
  zone_width int NOT NULL,
  zone_height int NOT NULL,
  PRIMARY KEY (id_scene, id_product, x_axis, y_axis)
);

CREATE TABLE PREFIX_search_engine (
  id_search_engine serial NOT NULL,
  server varchar(64) NOT NULL,
  getvar varchar(16) NOT NULL,
  PRIMARY KEY (id_search_engine)
);

CREATE TABLE PREFIX_search_index (
  id_product int NOT NULL,
  id_word int NOT NULL,
  weight int NOT NULL default '1',
  PRIMARY KEY (id_word, id_product)
);

CREATE TABLE PREFIX_search_word (
  id_word serial NOT NULL,
  id_lang int NOT NULL,
  word varchar(15) NOT NULL,
  PRIMARY KEY (id_word),
  UNIQUE (word)
);

CREATE TABLE PREFIX_state (
  id_state serial NOT NULL,
  id_country int NOT NULL,
  id_zone int NOT NULL,
  name varchar(64) NOT NULL,
  iso_code char(4) NOT NULL,
  tax_behavior smallint NOT NULL default '0',
  active int NOT NULL default '0',
  PRIMARY KEY (id_state)
);

CREATE TABLE PREFIX_subdomain (
  id_subdomain serial NOT NULL,
  name varchar(16) NOT NULL,
  PRIMARY KEY (id_subdomain)
);

CREATE TABLE PREFIX_supplier (
  id_supplier serial NOT NULL,
  name varchar(64) NOT NULL,
  date_add timestamp NOT NULL,
  date_upd timestamp NOT NULL,
  PRIMARY KEY (id_supplier)
);

CREATE TABLE PREFIX_supplier_lang (
  id_supplier int NOT NULL,
  id_lang int NOT NULL,
  description text,
  meta_title varchar(254) default NULL,
  meta_keywords varchar(254) default NULL,
  meta_description varchar(254) default NULL,
  PRIMARY KEY (id_supplier,id_lang)
);

CREATE TABLE PREFIX_tab (
  id_tab serial NOT NULL,
  id_parent int NOT NULL,
  class_name varchar(64) NOT NULL,
  module varchar(64) NULL,
  position int NOT NULL,
  PRIMARY KEY (id_tab)
);

CREATE TABLE PREFIX_tab_lang (
  id_lang int NOT NULL,
  id_tab int NOT NULL,
  name varchar(32) default NULL,
  PRIMARY KEY (id_tab,id_lang)
);

CREATE TABLE PREFIX_tag (
  id_tag serial NOT NULL,
  id_lang int NOT NULL,
  name varchar(32) NOT NULL,
  PRIMARY KEY (id_tag),
  UNIQUE (name)
);

CREATE TABLE PREFIX_tax (
  id_tax serial NOT NULL,
  rate float NOT NULL,
  PRIMARY KEY (id_tax)
);

CREATE TABLE PREFIX_tax_lang (
  id_tax int NOT NULL,
  id_lang int NOT NULL,
  name varchar(32) NOT NULL
);

CREATE TABLE PREFIX_tax_state (
  id_tax int NOT NULL,
  id_state int NOT NULL
);

CREATE TABLE PREFIX_tax_zone (
  id_tax int NOT NULL,
  id_zone int NOT NULL
);

CREATE TABLE PREFIX_timezone (
	id_timezone serial NOT NULL,
	name VARCHAR(32) NOT NULL,
	PRIMARY KEY (id_timezone)
);

CREATE TABLE PREFIX_web_browser (
  id_web_browser serial NOT NULL,
  name varchar(64) default NULL,
  PRIMARY KEY (id_web_browser)
);

CREATE TABLE PREFIX_zone (
  id_zone serial NOT NULL,
  name varchar(64) NOT NULL,
  active int NOT NULL default '0',
  enabled int NOT NULL default '0',
  PRIMARY KEY (id_zone)
);

ALTER TABLE PREFIX_accessory ADD FOREIGN KEY (id_product_1) REFERENCES PREFIX_product (id_product);
ALTER TABLE PREFIX_accessory ADD FOREIGN KEY (id_product_2) REFERENCES PREFIX_product (id_product);
ALTER TABLE PREFIX_address ADD FOREIGN KEY (id_customer) REFERENCES PREFIX_customer (id_customer);
ALTER TABLE PREFIX_attribute ADD FOREIGN KEY (id_attribute_group) REFERENCES PREFIX_attribute_group (id_attribute_group);
ALTER TABLE PREFIX_attribute_impact ADD FOREIGN KEY (id_product) REFERENCES PREFIX_product (id_product);
ALTER TABLE PREFIX_attribute_impact ADD FOREIGN KEY (id_attribute) REFERENCES PREFIX_attribute (id_attribute);
ALTER TABLE PREFIX_attribute_lang ADD FOREIGN KEY (id_lang) REFERENCES PREFIX_lang (id_lang);
ALTER TABLE PREFIX_carrier_lang ADD FOREIGN KEY (id_lang) REFERENCES PREFIX_lang (id_lang);
ALTER TABLE PREFIX_carrier_lang ADD FOREIGN KEY (id_carrier) REFERENCES PREFIX_carrier (id_carrier);
ALTER TABLE PREFIX_cart ADD FOREIGN KEY (id_customer) REFERENCES PREFIX_customer (id_customer);
ALTER TABLE PREFIX_cart_discount ADD FOREIGN KEY (id_cart) REFERENCES PREFIX_cart (id_cart);
ALTER TABLE PREFIX_cart_discount ADD FOREIGN KEY (id_discount) REFERENCES PREFIX_discount (id_discount);
ALTER TABLE PREFIX_cart_discount ADD FOREIGN KEY (id_discount) REFERENCES PREFIX_discount (id_discount);
ALTER TABLE PREFIX_cart_product ADD FOREIGN KEY (id_cart) REFERENCES PREFIX_cart (id_cart);
ALTER TABLE PREFIX_cart_product ADD FOREIGN KEY (id_product) REFERENCES PREFIX_product (id_product);
ALTER TABLE PREFIX_category ADD FOREIGN KEY (id_parent) REFERENCES PREFIX_category (id_category);
ALTER TABLE PREFIX_category_group ADD FOREIGN KEY (id_category) REFERENCES PREFIX_category (id_category);
ALTER TABLE PREFIX_category_group ADD FOREIGN KEY (id_group) REFERENCES PREFIX_group (id_group);
ALTER TABLE PREFIX_category_lang ADD FOREIGN KEY (id_category) REFERENCES PREFIX_category (id_category);
ALTER TABLE PREFIX_category_lang ADD FOREIGN KEY (id_lang) REFERENCES PREFIX_lang (id_lang);
ALTER TABLE PREFIX_category_product ADD FOREIGN KEY (id_category) REFERENCES PREFIX_category (id_category);
ALTER TABLE PREFIX_category_product ADD FOREIGN KEY (id_product) REFERENCES PREFIX_product (id_product);
ALTER TABLE PREFIX_connections ADD FOREIGN KEY (id_guest) REFERENCES PREFIX_guest (id_guest);
ALTER TABLE PREFIX_connections_source ADD FOREIGN KEY (id_connections) REFERENCES PREFIX_connections (id_connections);
ALTER TABLE PREFIX_contact_lang ADD FOREIGN KEY (id_contact) REFERENCES PREFIX_contact (id_contact);
ALTER TABLE PREFIX_contact_lang ADD FOREIGN KEY (id_lang) REFERENCES PREFIX_lang (id_lang);
ALTER TABLE PREFIX_country ADD FOREIGN KEY (id_zone) REFERENCES PREFIX_zone (id_zone);
ALTER TABLE PREFIX_country_lang ADD FOREIGN KEY (id_country) REFERENCES PREFIX_country (id_country);
ALTER TABLE PREFIX_country_lang ADD FOREIGN KEY (id_lang) REFERENCES PREFIX_lang (id_lang);
ALTER TABLE PREFIX_delivery ADD FOREIGN KEY (id_zone) REFERENCES PREFIX_zone (id_zone);
ALTER TABLE PREFIX_delivery ADD FOREIGN KEY (id_carrier) REFERENCES PREFIX_carrier (id_carrier);
ALTER TABLE PREFIX_delivery ADD FOREIGN KEY (id_zone) REFERENCES PREFIX_zone (id_zone);
ALTER TABLE PREFIX_discount ADD FOREIGN KEY (id_customer) REFERENCES PREFIX_customer (id_customer);
ALTER TABLE PREFIX_feature_value ADD FOREIGN KEY (id_feature) REFERENCES PREFIX_feature (id_feature);
ALTER TABLE PREFIX_group_lang ADD FOREIGN KEY (id_group) REFERENCES PREFIX_group (id_group);
ALTER TABLE PREFIX_group_lang ADD FOREIGN KEY (id_lang) REFERENCES PREFIX_lang (id_lang);
ALTER TABLE PREFIX_guest ADD FOREIGN KEY (id_customer) REFERENCES PREFIX_customer (id_customer);
ALTER TABLE PREFIX_hook_module ADD FOREIGN KEY (id_hook) REFERENCES PREFIX_hook (id_hook);
ALTER TABLE PREFIX_image ADD FOREIGN KEY (id_product) REFERENCES PREFIX_product (id_product);
ALTER TABLE PREFIX_image_lang ADD FOREIGN KEY (id_image) REFERENCES PREFIX_image (id_image);
ALTER TABLE PREFIX_image_lang ADD FOREIGN KEY (id_lang) REFERENCES PREFIX_lang (id_lang);
ALTER TABLE PREFIX_message ADD FOREIGN KEY (id_order) REFERENCES PREFIX_orders (id_order);
ALTER TABLE PREFIX_orders ADD FOREIGN KEY (id_customer) REFERENCES PREFIX_customer (id_customer);
ALTER TABLE PREFIX_orders ADD FOREIGN KEY (id_cart) REFERENCES PREFIX_cart (id_cart);
ALTER TABLE PREFIX_order_detail ADD FOREIGN KEY (id_order) REFERENCES PREFIX_orders (id_order);
ALTER TABLE PREFIX_order_discount ADD FOREIGN KEY (id_order) REFERENCES PREFIX_orders (id_order);
ALTER TABLE PREFIX_order_history ADD FOREIGN KEY (id_order) REFERENCES PREFIX_orders (id_order);
ALTER TABLE PREFIX_order_return ADD FOREIGN KEY (id_customer) REFERENCES PREFIX_customer (id_customer);
ALTER TABLE PREFIX_order_return_state_lang ADD FOREIGN KEY (id_order_return_state) REFERENCES PREFIX_order_return_state (id_order_return_state);
ALTER TABLE PREFIX_order_return_state_lang ADD FOREIGN KEY (id_lang) REFERENCES PREFIX_lang (id_lang);
ALTER TABLE PREFIX_order_slip ADD FOREIGN KEY (id_customer) REFERENCES PREFIX_customer (id_customer);
ALTER TABLE PREFIX_order_state_lang ADD FOREIGN KEY (id_order_state) REFERENCES PREFIX_order_state (id_order_state);
ALTER TABLE PREFIX_order_state_lang ADD FOREIGN KEY (id_lang) REFERENCES PREFIX_lang (id_lang);
ALTER TABLE PREFIX_page ADD FOREIGN KEY (id_page_type) REFERENCES PREFIX_page_type (id_page_type);
-- ALTER TABLE PREFIX_page ADD FOREIGN KEY (id_object) REFERENCES PREFIX_object (id_object);
ALTER TABLE PREFIX_product ADD FOREIGN KEY (id_supplier) REFERENCES PREFIX_supplier (id_supplier);
ALTER TABLE PREFIX_product ADD FOREIGN KEY (id_manufacturer) REFERENCES PREFIX_manufacturer (id_manufacturer);
ALTER TABLE PREFIX_product_attribute ADD FOREIGN KEY (id_product) REFERENCES PREFIX_product (id_product);
ALTER TABLE PREFIX_product_attribute_image ADD FOREIGN KEY (id_image) REFERENCES PREFIX_image (id_image);
ALTER TABLE PREFIX_product_lang ADD FOREIGN KEY (id_product) REFERENCES PREFIX_product (id_product);
ALTER TABLE PREFIX_product_lang ADD FOREIGN KEY (id_lang) REFERENCES PREFIX_lang (id_lang);
ALTER TABLE PREFIX_product_lang ADD FOREIGN KEY (id_lang) REFERENCES PREFIX_lang (id_lang);
ALTER TABLE PREFIX_search_word ADD FOREIGN KEY (id_lang) REFERENCES PREFIX_lang (id_lang);
ALTER TABLE PREFIX_tax_lang ADD FOREIGN KEY (id_tax) REFERENCES PREFIX_tax (id_tax);
ALTER TABLE PREFIX_tax_lang ADD FOREIGN KEY (id_lang) REFERENCES PREFIX_lang (id_lang);
ALTER TABLE PREFIX_tax_state ADD FOREIGN KEY (id_tax) REFERENCES PREFIX_tax (id_tax);
ALTER TABLE PREFIX_tax_state ADD FOREIGN KEY (id_state) REFERENCES PREFIX_state (id_state);
ALTER TABLE PREFIX_tax_zone ADD FOREIGN KEY (id_tax) REFERENCES PREFIX_tax (id_tax);
ALTER TABLE PREFIX_tax_zone ADD FOREIGN KEY (id_zone) REFERENCES PREFIX_zone (id_zone);
