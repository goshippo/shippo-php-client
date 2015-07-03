<?php
/* This example demonstrates how to purchase a label for an international shipment.
Creating domestic shipment would follow a similiar proccess but would not require
the creation of CustomsItems and CustomsDeclaration objects. */
require_once('lib/Shippo.php');

//replace <USERNAME> and <PASSWORD> with your credentials
Shippo::setCredentials("<username>", "<password>");

//example fromAddress array object
$fromAddress = array(
    'object_purpose' => 'PURCHASE',
    'name' => 'Laura Behrens Wu',
    'company' => 'Shippo',
    'street1' => '215 Clayton St.',
    'city' => 'San Francisco',
    'state' => 'CA',
    'zip' => '94117',
    'country' => 'US',
    'phone' => '+1 555 341 9393',
    'email' => 'laura@goshippo.com');

//example fromAddress array object
$toAddress = array(
    'object_purpose' => 'PURCHASE',
    'name' => 'Mr Hippo"',
    'company' => 'London Zoo"',
    'street1' => 'Regents Park',
    'street2' => 'Outer Cir',
    'city' => 'LONDON',
    'state' => '',
    'zip' => 'NW1 4RY',
    'country' => 'GB',
    'phone' => '+1 555 341 9393',
    'email' => 'mrhippo@goshippo.com');

//example fromAddress array object
$parcel = array(
    'length'=> '5',
    'width'=> '5',
    'height'=> '5',
    'distance_unit'=> 'in',
    'weight'=> '2',
    'mass_unit'=> 'lb',
);

//example CustomsItems object. This is only required for int'l shipment only.
$customs_item = array(
    'description'=> 'T-Shirt',
    'quantity'=> '2',
    'net_weight'=> '1',
    'mass_unit'=> 'lb',
    'value_amount'=> '20',
    'value_currency'=> 'USD',
    'origin_country'=> 'US');

#Creating the CustomsDeclaration
#(CustomsDeclarations are only required for international shipments)
$customs_declaration = Shippo_CustomsDeclaration::create(
array(
    'contents_type'=> 'MERCHANDISE',
    'contents_explanation'=> 'T-Shirt purchase',
    'non_delivery_option'=> 'RETURN',
    'certify'=> 'true',
    'certify_signer'=> 'Laura Behrens Wu',
    'items'=> array($customs_item)
));

//Creating the shipment object. In this example, the objects are directly passed to the 
//Shipment.create method, Alternatively, the Address and Parcel objects could be created 
//using Address.create(..) and Parcel.create(..) functions respectively.
$shipment = Shippo_Shipment::create(
array(
    'object_purpose'=> 'PURCHASE',
    'address_from'=> $fromAddress,
    'address_to'=> $toAddress,
    'parcel'=> $parcel,
    'submission_type'=> 'PICKUP',
    'insurance_amount'=> '30',
    'insurance_currency'=> 'USD',
    'customs_declaration'=> $customs_declaration["object_id"]
));

//Wait for rates to be generated
$attempts = 0;
while (($shipment["object_status"] == "QUEUED" || $shipment["object_status"] == "WAITING") && $attempts < 10){
    $shipment = Shippo_Shipment::retrieve($shipment["object_id"]);
    sleep(1);
    $attempts +=1;}

//Get all rates for shipment.
$rates = Shippo_Shipment::get_shipping_rates(array('id'=> $shipment["object_id"]));

//Get the first rate in the rates results.
$rate = $rates["results"][0];

// Purchase the desired rate
$transaction = Shippo_Transaction::create(array('rate'=> $rate["object_id"]));

//Wait for transaction to be proccessed
$attempts = 0;
while (($transaction["object_status"] == "QUEUED" || $transaction["object_status"] == "WAITING") && $attempts < 10){
    $transaction = Shippo_Transaction::retrieve($transaction["object_id"]);
    sleep(1);
    $attempts +=1;}

//label_url and tracking_number
if ($transaction["object_status"] == "SUCCESS"){
    echo($transaction["label_url"]);
    echo("\n");
    echo($transaction["tracking_number"]);
}else {
    echo($transaction["messages"]);
}
?>
