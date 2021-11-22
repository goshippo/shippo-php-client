<?php
/* 
In this tutorial we have an order with a sender address,
recipient address and parcel. The shipment is going from the 
United States to an international location.

Sample output:
--> Shipping label url: https://shippo-delivery-east.s3.amazonaws.com/785d52895d5a464cae35730a9f65ef28.pdf?Signature=TBmzL5S5mJ8BdzcacMQonaObZzE%3D&Expires=1510333519&AWSAccessKeyId=AKIAJGLCC5MYLLWIG42A
--> Shipping tracking number: CB150465075US

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
    'company' => 'Regents Park',
    'street1' => 'Outer Cir',
    'city' => 'London',
    'zip' => 'NW1 4RY',
    'country' => 'GB',
    'phone' => '+1 555 341 9393',
    'email' => 'ms-hippo@goshipppo.com',
	'metadata' =>  'For Order Number 123',
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

// Example CustomsItems object.
// The complete reference for customs object is here: https://goshippo.com/docs/reference#customsitems
$customs_item = array(
    'description' => 'T-Shirt',
    'quantity' => '2',
    'net_weight' => '400',
    'mass_unit' => 'g',
    'value_amount' => '20',
    'value_currency' => 'USD',
    'origin_country' => 'US',
    'tariff_number' => '',
);


// Creating the Customs Declaration
// The details on creating the CustomsDeclaration is here: https://goshippo.com/docs/reference#customsdeclarations
$customs_declaration = Shippo_CustomsDeclaration::create(
array(
    'contents_type'=> 'MERCHANDISE',
    'contents_explanation'=> 'T-Shirt purchase',
    'non_delivery_option'=> 'RETURN',
    'certify'=> 'true',
    'certify_signer'=> 'Mr Hippo',
    'items'=> array($customs_item),
));


// Example shipment object
// For complete reference to the shipment object: https://goshippo.com/docs/reference#shipments
// This object has async=false, indicating that the function will wait until all rates are generated before it returns.
// By default, Shippo handles responses asynchronously. However this will be depreciated soon. Learn more: https://goshippo.com/docs/async
$shipment = Shippo_Shipment::create(
    array(
        'address_from' => $from_address,
        'address_to' => $to_address,
        'parcels'=> array($parcel),
        'customs_declaration' => $customs_declaration -> object_id,
        'async' => false,
    )
);

// Filter rates by carrier
// Rates are stored in the `rates` array
// The details on the returned object are here: https://goshippo.com/docs/reference#rates
$filtered_rates = array_values(array_filter(
    $shipment['rates'],
    function($rate){
        return strtolower($rate['provider']) == 'usps' Or strtolower($rate['provider']) == 'dhl_express';
    }
));

$rate = $filtered_rates[1];
$selected_rate_carrier_account = $rate['carrier_account'];
// dhl_express usps
// Purchase the desired rate with a transaction request
// Set async=false, indicating that the function will wait until the carrier returns a shipping label before it returns
$transaction = Shippo_Transaction::create(array(
    'rate'=> $rate['object_id'],
    'async'=> false,
));

// Print the shipping label from label_url
// Get the tracking number from tracking_number
// Most international shipments require you to add 3 commercial invoices in the package's "pouch", a special envelope attached on the package. Shippo automatically creates these 3 copies for you, which will be returned in the Transaction's commercial_invoice field.
if ($transaction['status'] != 'SUCCESS'){
    echo "Transaction failed with messages:" . "\n";
    foreach ($transaction['messages'] as $message) {
        echo "--> " . $message . "\n";
    }
    exit;
}

$pickupTimeStart = date('Y-m-d H:i:s', time());
$pickupTimeEnd = date('Y-m-d H:i:s', time() + 60*60*24);

print_r(array(
    "carrier_account" => $selected_rate_carrier_account,
    "location" => array(
        "building_location_type" => "Knock on Door",
        "address" => $to_address
    ),
    "transactions" => array($transaction->object_id),
    "requested_start_time" => $pickupTimeStart,
    "requested_end_time" => $pickupTimeEnd,
    "is_test" => true
));

$pickup = Shippo_Pickup::create(array(
    "carrier_account" => $selected_rate_carrier_account,
    "location" => array(
        "building_location_type" => "Knock on Door",
        "address" => $from_address
    ),
    "transactions" => array($transaction->object_id),
    "requested_start_time" => $pickupTimeStart,
    "requested_end_time" => $pickupTimeEnd,
    "is_test" => true
));
if ($pickup['status'] == 'SUCCESS'){
    echo "--> " . "Pickup has been scheduled\n";
} else {
    echo "Pickup failed with messages:" . "\n";
    foreach ($pickup['messages'] as $message) {
        echo "--> " . $message . "\n";
    }
}

// For more tutorals of address validation, tracking, returns, refunds, and other functionality, check out our
// complete documentation: https://goshippo.com/docs/
?>
