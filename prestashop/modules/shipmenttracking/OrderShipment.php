<?php

/**
  * OrderShipment class, OrderShipment.php
  * Order shipment management
  * @category classes
  *
  * @author Egil Möller <egil.moller@freecode.no>
  * @copyright PrestaShop, FreeCode AS
  * @license http://www.opensource.org/licenses/osl-3.0.php Open-source licence 3.0
  * @license http://www.gnu.org/licenses/gpl.html GNU General Public License 3.0
  * @version 0.9
  *
  */

class		OrderShipment extends ObjectModel
{
	public		$id;

	/** @var integer Employee ID */
	public 		$id_employee;

	/** @var integer Order ID */
	public 		$id_order;

	/** @var string Shipment tracking number */
	public 		$tracking_number;

	/** @var string Shiptrack carrier code */
	public 		$shiptrackcode;

	/** @var string Object creation date */
	public 		$date_add;
	
	protected	$fieldsRequired = array();
	protected	$fieldsValidate = array('id_employee' => 'isUnsignedId', 'id_order' => 'isUnsignedId');

	protected 	$table = 'order_shipment';
	protected 	$identifier = 'id_order_shipment';

	public function getFields()
	{
		parent::validateFields();

		$fields = array();
		$fields['id_order_shipment'] = intval($this->id);
		$fields['id_employee'] = intval($this->id_employee);
		$fields['id_order'] = intval($this->id_order);
		$fields['tracking_number'] = pSQL($this->tracking_number);
		$fields['date_add'] = pSQL($this->date_add);

		return $fields;
	}

	public function delete()
	{
		if (!Db::getInstance()->Execute('DELETE FROM `'._DB_PREFIX_.'order:shipment` WHERE `id_order_shipment` = '.intval($this->id)))
			return false;
		return parent::delete();
	}

	static public function get_from_order($id_order)
	{
		$id_order = pSQL($id_order);
		$order = new Order($id_order);


		$sql = "select url from PREFIX_carrier where id_carrier = $order->id_carrier";
		$sql = str_replace('PREFIX_', _DB_PREFIX_, $sql);
		$row = Db::getInstance()->getRow($sql);
		$url = $row['url'];

		$sql = "select id_order_shipment from PREFIX_order_shipment where id_order = {$id_order} order by date_add desc";
		$sql = str_replace('PREFIX_', _DB_PREFIX_, $sql);
		$rows = Db::getInstance()->ExecuteS($sql);
		$results = array();
		foreach ($rows as $row) {
			$shipment = new self($row['id_order_shipment']);
			$shipment->url = str_replace('@', $shipment->tracking_number, $url);
			$results[] = $shipment;
		}
		return $results;
	}
}
