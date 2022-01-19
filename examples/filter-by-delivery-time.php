<?php
/* 
In this tutorial we have an order with a sender address,
recipient address and parcel information that we need to ship.
In addition to that we know that the customer expects the
shipment to arrive within 3 days. We want to purchase
the cheapest shipping label with a transit time <= 3 days.

Sample output:
--> Shipping label url: https://shippo-delivery-east.s3.amazonaws.com/222d4b4df76d499f95f3518040679319.pdf?Signature=JbQkYtUKT%2Fl1zUOqgUziCydsEcQ%3D&Expires=1510333279&AWSAccessKeyId=AKIAJGLCC5MYLLWIG42A
--> Shipping tracking number: 9205590164917330560021

Before running it, remember to do
    composer install
*/

require_once(__DIR__ . '../../vendor/autoload.php');

// or if you do not have or want the composer autoload feature do
// require_once('path/to/shippo/library/folder/' . 'lib/Shippo.php');

// Replace <API-KEY> with your credentials from https://app.goshippo.com/api/
Shippo::setApiKey('<API-KEY>');

// for demo purposes we set the max. transit time here
const MAX_TRANSIT_TIME_DAYS = 3;

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

// Filter rates by MAX_TRANSIT_TIME_DAYS
// Rates are stored in the `rates` array
// The details on the returned object are here: https://goshippo.com/docs/reference#rates
$eligible_rates = array_values(array_filter(
    $shipment['rates'],
    function($rate){
        return $rate['estimated_days'] <= MAX_TRANSIT_TIME_DAYS;
    }
));

// Select the cheapest rate from eligible service levels
usort($eligible_rates, function($a, $b) {
    // usort function casts the result to int, so be aware that returning $a['amount'] - $b['amount']
    // may state that 2 amounts are equal when they are not
    if ($a['amount'] < $b['amount']) return -1;
    if ($a['amount'] > $b['amount']) return 1;
    return 0;
});


// Purchase the desired rate with a transaction request
// Set async=false, indicating that the function will wait until the carrier returns a shipping label before it returns
$transaction = Shippo_Transaction::create(array(
    'rate'=> $eligible_rates[0]['object_id'],
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
