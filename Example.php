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
    'name' => 'Shippo Itle"',
    'company' => 'Shippo',
    'street1' => '215 Clayton St.',
    'city' => 'San Francisco',
    'state' => 'CA',
    'zip' => '94117',
    'country' => 'US',
    'phone' => '+1 555 341 9393',
    'email' => 'support@goshipppo.com');

// example fromAddress
$toAddress = array(
    'object_purpose' => 'PURCHASE',
    'name' => 'Mr Hippo"',
    'company' => 'San Diego Zoo"',
    'street1' => '2920 Zoo Drive"',
    'city' => 'San Diego',
    'state' => 'CA',
    'zip' => '92101',
    'country' => 'US',
    'phone' => '+1 555 341 9393',
    'email' => 'hippo@goshipppo.com');

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
    'submission_type'=> 'PICKUP',
    'insurance_amount'=> '30',
    'insurance_currency'=> 'USD'
));

// Wait for rates to be generated
$ratingStartTime=time();
while (($shipment["object_status"] == "QUEUED" || $shipment["object_status"] == "WAITING")){
    $shipment = Shippo_Shipment::retrieve($shipment["object_id"]);
    usleep(200000); //sleeping 200ms
    if (time()-$ratingStartTime>25) break;
    }

// Get all rates for shipment.
$rates = Shippo_Shipment::get_shipping_rates(array('id'=> $shipment["object_id"]));

// Get the first rate from the rates results
$rate = $rates["results"][0];

// Purchase the desired rate
$transaction = Shippo_Transaction::create(array('rate'=> $rate["object_id"]));

// Wait for transaction to be proccessed
$transactionStartTime=time();
while (($transaction["object_status"] == "QUEUED" || $transaction["object_status"] == "WAITING")){
    $transaction = Shippo_Transaction::retrieve($transaction["object_id"]);
    usleep(200000);  //sleeping 200ms
    if (time()-$transactionStartTime>25) break;
    }

// label_url and tracking_number
if ($transaction["object_status"] == "SUCCESS"){
    echo($transaction["label_url"]);
    echo("\n");
    echo($transaction["tracking_number"]);
}else {
    echo($transaction["messages"]);
}
?>