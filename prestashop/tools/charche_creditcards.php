<?php

include(dirname(__FILE__).'/../config/config.inc.php');
include(dirname(__FILE__).'/../init.php');
include(dirname(__FILE__).'/../modules/netaxept/netaxept.php');

try {

$mod = new Netaxept();
$wsdl_url = $mod->getNetaxeptWsdlUrl();

$sql = "
 select
  *
 from
  (select
    h1.*
   from
    order_history h1
    left outer join order_history h2 on
      h1.id_order = h2.id_order
      and h1.date_add < h2.date_add
   where
    h2.date_add is null) as s
  join order_state_lang as l on
   s.id_order_state = l.id_order_state
   and l.id_lang = 1
   and l.name = 'Awaiting fund transfer'
  join orders o on
   o.id_order = s.id_order
  join currency c on
   o.id_currency = c.id_currency;
";
$rows = Db::getInstance()->ExecuteS($sql);
foreach ($rows as $row) {
  $line = "";
  foreach ($row as $key => $value) {
    $line .= "$key=$value ";
  }
  echo "Charging for order: {$line}\n";

  $mid_token = $mod->getMidToken($row['iso_code']);
  $token = $mid_token['token'];
  $merchant_id = $mid_token['merchant_id'];

  $capture_request = array(
    "token" => $token,
    "merchantId" => $merchant_id,
    "transactionId" => $row['payment_reference'], 
    "description" => 'Withdrawal', 
    "transactionAmount" => intval(floatval($row['total_paid_real']) * 100), // WTF?! But that's how their API works...
    "transactionReconRef" => 'Withdrawal'
  );
  $client = new SoapClient($wsdl_url, array('trace' => true, 'exceptions' => true));

  try {
    $error = null;
    $response = $client->__call('Capture' , array("parameters" => $capture_request));
    if ($response->CaptureResult->ResponseCode != 'OK') {
      $error = $response->CaptureResult->ResponseCode + ": " + $response->CaptureResult->ResponseText;
    }
  } catch (Exception $e) {
    $error = $e->getMessage();
  }

  if ($error == null) {
    $state = 'Funds transferred';
  } else {
    $state = 'Error transferring funds';
    $sql = "
      insert into
       message (id_cart, id_customer, id_employee, id_order, message, private, date_add)
       select '{$row['id_cart']}', '{$row['id_customer']}', '{$row['id_employee']}', '{$row['id_order']}', '{$error}', 0, now();
    ";
    Db::getInstance()->ExecuteS($sql);
  }
  $sql = "
    insert into
     order_history (id_employee, id_order, id_order_state, date_add)
     select 0, '{$row['id_order']}', id_order_state, now() from order_state_lang where id_lang=1 and name='{$state}';
  ";
  Db::getInstance()->ExecuteS($sql);
}

} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}
