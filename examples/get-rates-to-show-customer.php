<?php
/* 
In this tutorial we have an order with a sender address,
recipient address and parcel. We will retrieve all avail-
able shipping rates, display them to the user and purchase
a label after the user has selected a rate.

Sample output:
Available rates:
--> USPS - Priority Mail Express
  --> Amount: 28.31
  --> Days to delivery: 1
--> USPS - Priority Mail
  --> Amount: 6.25
  --> Days to delivery: 2
--> USPS - Parcel Select
  --> Amount: 6.72
  --> Days to delivery: 7

--> Shipping label url: https://shippo-delivery-east.s3.amazonaws.com/fb199cfef3164852bf0eea04c082ba6e.pdf?Signature=rMndAoIyKPekw7PZtwzrnOqFlOY%3D&Expires=1510333434&AWSAccessKeyId=AKIAJGLCC5MYLLWIG42A
--> Shipping tracking number: 9205590164917330560380

Before running it, remember to do
    composer install
*/

require_once(__DIR__ . '../../vendor/autoload.php');

// or if you do not have or want the composer autoload feature do
// require_once('path/to/shippo/library/folder/' . 'lib/Shippo.php');

// Replace <API-KEY> with your credentials from https://app.goshippo.com/api/
Shippo::setApiKey('<API-KEY>');


// Example from_address array
// The complete refence for the address object is available here: https://goshippo.com/docs/reference#addresses
$from_address = array(
    'name' => 'Mr Hippo',
    'company' => 'Shippo',
    'street1' => '215 Clayton St.',
    'city' => 'San Francisco',
    'state' => 'CA',
    'zip' => '94117',
    'country' => 'US',
    'phone' => '+1 555 341 9393',
    'email' => 'mr-hippo@goshipppo.com',
);

// Example to_address array
// The complete refence for the address object is available here: https://goshippo.com/docs/reference#addresses
$to_address = array(
    'name' => 'Ms Hippo',
    'company' => 'San Diego Zoo',
    'street1' => '2920 Zoo Drive',
    'city' => 'San Diego',
    'state' => 'CA',
    'zip' => '92101',
    'country' => 'US',
    'phone' => '+1 555 341 9393',
    'email' => 'ms-hippo@goshipppo.com',
);

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

// Example shipment object
// For complete reference to the shipment object: https://goshippo.com/docs/reference#shipments
// This object has async=false, indicating that the function will wait until all rates are generated before it returns.
// By default, Shippo handles responses asynchronously. However this will be depreciated soon. Learn more: https://goshippo.com/docs/async
$shipment = Shippo_Shipment::create(
array(
    'address_from'=> $from_address,
    'address_to'=> $to_address,
    'parcels'=> array($parcel),
    'async'=> false,
));

// Rates are stored in the `rates` array inside the shipment object
$rates = $shipment['rates'];

// You can now show those rates to the user in your UI.
// Most likely you want to show some of the following fields:
//  - provider (carrier name)
//  - servicelevel_name
//  - amount (price of label - you could add e.g. a 10% markup here)
//  - days (transit time)
// Don't forget to store the `object_id` of each Rate so that you can use it for the label purchase later.
// The details on all of the fields in the returned object are here: https://goshippo.com/docs/reference#rates
echo "Available rates:" . "\n";
foreach ($rates as $rate) {
    echo "--> " . $rate['provider'] . " - " . $rate['servicelevel']['name'] . "\n";
    echo "  --> " . "Amount: "             . $rate['amount'] . "\n";
    echo "  --> " . "Days to delivery: "   . $rate['days'] . "\n";
}
echo "\n";

// This would be the index of the rate selected by the user
$selected_rate_index = count($rates) - 1;

// After the user has selected a rate, use the corresponding object_id
$selected_rate = $rates[$selected_rate_index];
$selected_rate_object_id = $selected_rate['object_id'];


// Purchase the desired rate with a transaction request
// Set async=false, indicating that the function will wait until the carrier returns a shipping label before it returns
$transaction = Shippo_Transaction::create(array(
    'rate'=> $selected_rate_object_id,
    'async'=> false,
));

// Print the shipping label from label_url
// Get the tracking number from tracking_number
if ($transaction['status'] == 'SUCCESS'){
    echo "--> " . "Shipping label url: " . $transaction['label_url'] . "\n";
    echo "--> " . "Shipping tracking number: " . $transaction['tracking_number'] . "\n";
} else {
    echo "Transaction failed with messages:" . "\n";
    foreach ($transaction['messages'] as $message) {
        echo "--> " . $message . "\n";
    }
}

// For more tutorals of address validation, tracking, returns, refunds, and other functionality, check out our
// complete documentation: https://goshippo.com/docs/
?>
