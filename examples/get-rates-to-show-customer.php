<?php
/* 
In this tutorial we have an order with a sender address,
recipient address and parcel. We will retrieve all avail-
able shipping rates, display them to the user and purchase
a label after the user has selected a rate.
*/
require_once('lib/Shippo.php');

// Replace <API-KEY> with your credentials
Shippo::setApiKey("<API-KEY>");

// Example from_address array
// The complete refence for the address object is available here: https://goshippo.com/docs/reference#addresses
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

// Example to_address array
// The complete refence for the address object is available here: https://goshippo.com/docs/reference#addresses
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

// Parcel information array
// The complete reference for parcel object is here: https://goshippo.com/docs/reference#parcels
$parcel = array(
    'length'=> '5',
    'width'=> '5',
    'height'=> '5',
    'distance_unit'=> 'in',
    'weight'=> '2',
    'mass_unit'=> 'lb',
);

/* 
Example shipment object
For complete reference to the shipment object: https://goshippo.com/docs/reference#shipments
This object has async=False, indicating that the function will wait until all rates are generated before it returns.
By default, Shippo handles responses asynchronously. However this will be depreciated soon. Learn more: https://goshippo.com/docs/async
*/

$shipment = Shippo_Shipment::create(
array(
    'object_purpose'=> 'PURCHASE',
    'address_from'=> $fromAddress,
    'address_to'=> $toAddress,
    'parcel'=> $parcel,
    'async'=> false
));

/* 
Rates are stored in the `rates_list` array inside the shipment object
*/
$rates = $shipment["rates_list"];
/*
You can now show those rates to the user in your UI. 
Most likely you want to show some of the following fields:
- provider (carrier name)
- servicelevel_name
- amount (price of label - you could add e.g. a 10% markup here)
- days (transit time)
Don't forget to store the `object_id` of each Rate so that you can use it for the label purchase later.
The details on all of the fields in the returned object are here: https://goshippo.com/docs/reference#rates
*/


// After the user has selected a rate, use the corresponding object_id
$selected_rate_object_id = '<SELECTED-RATE-OBJECT-ID>'


// Purchase the desired rate with a transaction request
// Set async=False, indicating that the function will wait until the carrier returns a shipping label before it returns
$transaction = Shippo_Transaction::create(array(
    'rate'=> $selected_rate_object_id,
    'async'=> false
));

// Print the shipping label from label_url 
// Get the tracking number from tracking_number
if ($transaction["object_status"] == "SUCCESS"){
    echo($transaction["label_url"]);
    echo("\n");
    echo($transaction["tracking_number"]);
} else {
    foreach ($transaction["messages"] as $message) {
        echo($message);
    }
}
// For more tutorals of address validation, tracking, returns, refunds, and other functionality, check out our
// complete documentation: https://goshippo.com/docs/
?>
