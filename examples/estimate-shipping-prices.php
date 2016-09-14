<?php
	
/*
In this tutorial we want to calculate our average shipping costs so 
we set pricing for customers.
We have a sender address, a parcel and a set of delivery zip codes.
We will retrieve all available shipping rates for each delivery
address and calculate the min, max and average price for specific
transit time windows (next day, 3 days, 7 days).

Sample output:
For a delivery window of 1 days:
--> Min. costs: 5.81
--> Max. costs: 106.85
--> Avg. costs: 46.91
For a delivery window of 3 days:
--> Min. costs: 5.81
--> Max. costs: 106.85
--> Avg. costs: 34.99
For a delivery window of 7 days:
--> Min. costs: 3.22
--> Max. costs: 106.85
--> Avg. costs: 29.95
*/	



// Define delivery windows in max. days
// Pick an east coast, a west coast and a mid-west destination
$delivery_windows = array(1, 3, 7);
$destination_zip_codes = array('10007', '60290', '95122');	
	
require_once('lib/Shippo.php');

// Replace <API-KEY> with your credentials
Shippo::setApiKey("<API-KEY>");

// Example from_address array
// The complete refence for the address object is available here: https://goshippo.com/docs/reference#addresses
$fromAddress = array(
    'object_purpose' => 'QUOTE',
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
    'object_purpose' => 'QUOTE',
    'country' => 'US');

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
For each destination address we now create a Shipment object
and store the min, max, average shipping rates per delivery window.
*/

foreach ($destination_zip_codes as $zip_code)
{
	// Change delivery address to current delivery address
	$toAddress['zip'] = $zip_code;
	
	
	/* Creating the shipment object. async=False indicates that the function will wait until all
	  rates are generated before it returns.
	  The reference for the shipment object is here: https://goshippo.com/docs/reference#shipments
	  By default Shippo API operates on an async basis. You can read about our async flow here: https://goshippo.com/docs/async
	*/


		$shipment = Shippo_Shipment::create(
			array(
    			'object_purpose'=> 'QUOTE',
   			 	'address_from'=> $fromAddress,
    			'address_to'=> $toAddress,
    			'parcel'=> $parcel,
    			'async'=> false
			));
			/* 	
				Rates are stored in the `rates_list` array
				The details on the returned object are here: https://goshippo.com/docs/reference#rates
			*/
			
			
			/* do some loop magic to get the right rates here*/
			
		
		
}	
	/* Show output */
?>