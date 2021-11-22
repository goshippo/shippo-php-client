<?php
/* 
In this tutorial we have an order with a sender address,
recipient address and parcel information that we need to ship.
We want to get the parcel to the customer as soon as possible, 
with a max delivery time of 3 days.

Sample output:
--> Shipping label url: https://shippo-delivery-east.s3.amazonaws.com/8c552d19629b4556ad98d00609c93b2d.pdf?Signature=5oeRPPnfsUVXCg1MSobRlIzvWfU%3D&Expires=1510333540&AWSAccessKeyId=AKIAJGLCC5MYLLWIG42A
--> Shipping tracking number: 9270190164917300871702

Before running it, remember to do
    composer install
*/

require_once(__DIR__ . '../../vendor/autoload.php');

// or if you do not have or want the composer autoload feature do
// require_once('path/to/shippo/library/folder/' . 'lib/Shippo.php');

// Replace <API-KEY> with your credentials from https://app.goshippo.com/api/
Shippo::setApiKey('<API-KEY>');


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

// Select the fastest from eligible service levels
usort($eligible_rates, function($a, $b) {
    return $a['estimated_days'] - $b['estimated_days'];
});

// Purchase the desired rate with a transaction request
// Set async=false, indicating that the function will wait until the carrier returns a shipping label before it returns
// Need different label format or to pass meta data along? Full reference: https://goshippo.com/docs/reference#transactions
$transaction = Shippo_Transaction::create(array(
    'rate'=> $eligible_rates[0]['object_id'],
    'async'=> false,
));

// Print the shipping label from label_url
// Get the tracking number from tracking_number
// Description of the full returned transaction https://goshippo.com/docs/reference#transactions
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
