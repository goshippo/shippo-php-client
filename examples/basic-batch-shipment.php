<?php
/* 
In this tutorial we will create a batch shipment,
add and remove shipments from the batch, and purchase the batch shipment.

Sample output:
--> Batch created with id: 6590eecf10f04c79a41bc6ffc6406b6f
--> Shipment created with id: a881b47ee9b04089b543588051ad49b5
--> Batch object 6590eecf10f04c79a41bc6ffc6406b6f has status VALIDATING
Waiting for the batch to validate
--> Batch object 6590eecf10f04c79a41bc6ffc6406b6f has status VALID
--> batch now contains shipments: {
    "next": null,
    "previous": null,
    "results": [
        {
            "metadata": "",
            "carrier_account": null,
            "servicelevel_token": null,
            "shipment": "188f0891ee3e44888b577985c3cafb0f",
            "transaction": null,
            "object_id": "8940f9546c7040f58d84465dad496051",
            "status": "VALID",
            "messages": []
        },
        {
            "metadata": "",
            "carrier_account": null,
            "servicelevel_token": null,
            "shipment": "a881b47ee9b04089b543588051ad49b5",
            "transaction": null,
            "object_id": "ecd6e8248ac54ec9821fb25e3398f815",
            "status": "VALID",
            "messages": []
        }
    ]
}
--> batch now contains shipments: {
    "next": null,
    "previous": null,
    "results": [
        {
            "metadata": "",
            "carrier_account": null,
            "servicelevel_token": null,
            "shipment": "a881b47ee9b04089b543588051ad49b5",
            "transaction": null,
            "object_id": "ecd6e8248ac54ec9821fb25e3398f815",
            "status": "VALID",
            "messages": []
        }
    ]
}
--> Batch object 6590eecf10f04c79a41bc6ffc6406b6f has status PURCHASING
Before running it, remember to do
    composer install
*/

require_once(__DIR__ . '../../vendor/autoload.php');
// or if you do not have or want the composer autoload feature do
// require_once('path/to/shippo/library/folder/' . 'lib/Shippo.php');

// Replace <API-KEY> with your credentials from https://app.goshippo.com/api/
Shippo::setApiKey('<API-KEY>');


//Example data to create a batch shipment
//The complete reference to batch shipment creation is available here: https://goshippo.com/docs/reference#batches-create
$carrier = '<YOUR CARRIER ACCOUNT PRIVATE KEY>';

$data = array(
    'default_carrier_account' => $carrier,
    'default_servicelevel_token' => 'usps_priority',
    'label_filetype' => 'PDF_4x6',
    'metadata' => '',
    'batch_shipments' => array(
        array(
            'shipment' => array(    
                'address_from' => array(
                    'name' => 'Mr Hippo',
                    'street1' => '965 Mission St',
                    'street2' => 'Ste 201',
                    'city' => 'San Francisco',
                    'state' => 'CA',
                    'zip' => '94103',
                    'country' => 'US',
                    'phone' => '4151234567',
                    'email' => 'mrhippo@goshippo.com'
                ),
                'address_to' => array(
                    'name' => 'Mrs Hippo',
                    'company' => '',
                    'street1' => 'Broadway 1',
                    'street2' => '',
                    'city' => 'New York',
                    'state' => 'NY',
                    'zip' => '10007',
                    'country' => 'US',
                    'phone' => '4151234567',
                    'email' => 'mrshippo@goshippo.com'
                ),
                'parcels' => array(
                    array(
                        'length' => '5',
                        'width' => '5',
                        'height' => '5',
                        'distance_unit' => 'in',
                        'weight' => '2',
                        'mass_unit' => 'oz'
                    )
                )
            )
        )
    ) 
);

//Example of batch shipment creation
$batch = Shippo_Batch::create($data);
echo "--> " . "Batch created with id: " . $batch['object_id'] . "\n";

//Now we create a shipment to add to the newly created batch

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
echo "--> " . "Shipment created with id: " . $shipment['object_id'] . "\n";

//Example of retrieving a batch object to check its validation status
//For complete reference to the retrieve endpoint: https://goshippo.com/docs/reference#batches-retrieve
//This method of polling the batch validation status is for demo purposes only
//In practice it is advised to use the batch-create webhook in the user api dashboard: https://app.goshippo.com/api/
$MAX_TIMEOUT = 10;
$counter = 0;
while ($counter < $MAX_TIMEOUT) {
    $retrieved_batch = Shippo_Batch::retrieve($batch['object_id']);
    if ($retrieved_batch['status'] == 'VALID') {
        break;
    } else {
        $counter = $counter + 1;
        sleep(1);
    }
}
$retrieved_batch2 = Shippo_Batch::retrieve($batch['object_id']);
if ($retrieved_batch2['status'] == 'VALID') {
    echo "--> " . "Batch object " . $retrieved_batch2['object_id'] . " has status " . $retrieved_batch2['status'] . "\n";
    //example shipment to add to the batch
    $shipments_to_add = array(
        array('shipment' => $shipment['object_id'])
    );
    //add an array of shipment objects to the batch
    //For complete reference to the batch-add endpoint: https://goshippo.com/docs/reference#batches-add-shipments
    $added_batch = Shippo_Batch::add($batch['object_id'], $shipments_to_add);
    echo "--> " . "batch now contains shipments: " . $added_batch['batch_shipments'] . "\n";
    //example shipment to remove from the batch
    $shipments_to_remove = array(
        $added_batch['batch_shipments']['results'][0]
    );
    //remove an array of shipment objects from the batch
    //For complete reference to the batch-remove endpoint: https://goshippo.com/docs/reference#batches-remove-shipments
    $removed_batch = Shippo_Batch::remove($batch['object_id'], $shipments_to_remove);
    echo "--> " . "batch now contains shipments: " . $removed_batch['batch_shipments'] . "\n";
    //purchase the batch shipment
    //For complete reference to the batch-purchase endpoint: https://goshippo.com/docs/reference#batches-purchase
    $purchased_batch = Shippo_Batch::purchase($removed_batch['object_id']);
    echo "--> " . "Batch object " . $purchased_batch['object_id'] . " has status " . $purchased_batch['status'] . "\n";
} else {
    echo 'Batch shipment validation timed out' . "\n";
}

?>
