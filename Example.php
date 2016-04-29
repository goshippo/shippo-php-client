<?php
/* 
This example demonstrates how to purchase a label for a domestic US shipment.
*/
require_once('lib/Shippo.php');

// Replace <API-KEY> with your credentials
Shippo::setApiKey("<API-KEY>");

// example fromAddress
$fromAddress = array(
    'object_purpose' => 'PURCHASE',
    'name' => 'Mr Hippo"',
    'company' => 'Shippo',
    'street1' => '215 Clayton St.',
    'city' => 'San Francisco',
    'state' => 'CA',
    'zip' => '94117',
    'country' => 'US',
    'phone' => '+1 555 341 9393',
    'email' => 'mr-hippo@goshipppo.com');

// example fromAddress
$toAddress = array(
    'object_purpose' => 'PURCHASE',
    'name' => 'Ms Hippo"',
    'company' => 'San Diego Zoo"',
    'street1' => '2920 Zoo Drive"',
    'city' => 'San Diego',
    'state' => 'CA',
    'zip' => '92101',
    'country' => 'US',
    'phone' => '+1 555 341 9393',
    'email' => 'ms-hippo@goshipppo.com');

// example parcel
$parcel = array(
    'length'=> '5',
    'width'=> '5',
    'height'=> '5',
    'distance_unit'=> 'in',
    'weight'=> '2',
    'mass_unit'=> 'lb',
);

// example Shipment object
$shipment = Shippo_Shipment::create(
array(
    'object_purpose'=> 'PURCHASE',
    'address_from'=> $fromAddress,
    'address_to'=> $toAddress,
    'parcel'=> $parcel,
    'async'=> false
));

// Select the rate you want to purchase.
// We simply select the first rate in this example.
$rate = $shipment["rates_list"][0];

// Purchase the desired rate
$transaction = Shippo_Transaction::create(array(
    'rate'=> $rate["object_id"],
    'async'=> false
));

// label_url and tracking_number
if ($transaction["object_status"] == "SUCCESS"){
    echo($transaction["label_url"]);
    echo("\n");
    echo($transaction["tracking_number"]);
}else {
    foreach ($transaction["messages"] as $message) {
        echo($message);
    }
}
?>
