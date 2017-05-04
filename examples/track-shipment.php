<?php
/* 
In this tutorial we will get tracking information about a shipment using carrier information
and a tracking number. We will also register a tracking webhook using the Shippo API to receive 
tracking updates

Sample output:--> Carrier: usps
--> Shipping tracking number: 9205590164917312751089
--> Address from: {
    "city": "Las Vegas",
    "state": "NV",
    "zip": "89101",
    "country": "US"
}
--> Address to: {
    "city": "Spotsylvania",
    "state": "VA",
    "zip": "22551",
    "country": "US"
}
--> Tracking Status: {
    "object_created": "2016-07-23T20:35:26.129Z",
    "object_updated": "2016-07-23T20:35:26.129Z",
    "object_id": "5af525aa9bb2458c893020bc00d188f2",
    "status": "DELIVERED",
    "status_details": "Your shipment has been delivered at the destination mailbox.",
    "status_date": "2016-07-23T13:03:00Z",
    "location": {
        "city": "Spotsylvania",
        "state": "VA",
        "zip": "22551",
        "country": "US"
    }
}

Before running it, remember to do
    composer install
*/

require_once(__DIR__ . '../../vendor/autoload.php');

// or if you do not have or want the composer autoload feature do
// require_once('path/to/shippo/library/folder/' . 'lib/Shippo.php');

// Replace <API-KEY> with your credentials from https://app.goshippo.com/api/
Shippo::setApiKey('<API-KEY>'); 


//Example data for Track::get_status
//The complete reference for the tracking status endpoint is available here: https://goshippo.com/docs/reference#tracks-retrieve
$status_params = array(
    'id' => '9205590164917312751089',
    'carrier' => 'usps'
);

//Get the tracking status of a shipment using Shippo_Track::get_status
//The response is stored in $status
//The complete reference for the returned Tracking object is available here: https://goshippo.com/docs/reference#tracks
$status = Shippo_Track::get_status($status_params);

//Example data for Track::create
//The complete reference for the tracks-create endpoint is available here: https://goshippo.com/docs/reference#tracks-create
$create_params = array(
    'carrier' => 'usps',
    'tracking_number' => '9205590164917312751089',
    'metadata' => 'This is an optional field'
);

//The response is stored in $webhook response and is identical to the response of Track::get_status 
$webhook_response = Shippo_Track::create($create_params);


echo "--> " . "Carrier: " . $webhook_response['carrier'] . "\n";
echo "--> " . "Shipping tracking number: " . $webhook_response['tracking_number'] . "\n";
echo "--> " . "Address from: " . $webhook_response['address_from'] . "\n";
echo "--> " . "Address to: " . $webhook_response['address_to'] . "\n";
echo "--> " . "Tracking Status: " . $webhook_response['tracking_status'] . "\n";

?>
