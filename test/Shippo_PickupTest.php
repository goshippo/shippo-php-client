<?php

class Shippo_PickupTest extends TestCase
{

    /** @test */
    public function testValidCreate()
    {
        $shipment = self::getInternationalShipment();
        $filtered_rates = array_values(array_filter(
            $shipment['rates'],
            function($rate){
                return strtolower($rate['provider']) == 'usps' Or strtolower($rate['provider']) == 'dhl_express';
            }
        ));
        $rate = $filtered_rates[0];
        $transaction = Shippo_Transaction::create(array(
            'rate' => $rate->object_id,
            "async" => false,
            "label_file_type" => "PDF"
        ));
        $pickup = self::getDefaultPickup($transaction->object_id, $rate->carrier_account, $shipment->address_to);
        $retrieve_pickup = Shippo_Address::retrieve($address->object_id);
        $this->assertEquals($pickup->status, 'CONFIRMED');
    }
    
    /** @test */
    public function testInvalidCreate()
    {
        try {
            $invalid_account_id = 'XXXd8195e6e8a804f268380e99c9f24XXX';
            $pickup = self::getDefaultPickup($transaction->object_id, $invalid_account_id, $shipment->address_to);
        }
        catch (Exception $e) {
            $this->assertTrue(true);
        }
        
    }
    
    private function getDefaultPickup($transactionId, $carrierAccount, $addressTo)
    {
        $pickupTimeStart = date('Y-m-d H:i:s', time());
        $pickupTimeEnd = date('Y-m-d H:i:s', time() + 60*60*24);
        return Shippo_Pickup::create(array(
            
                "carrier_account" => $carrierAccount,
                "location" => array( 
                    "building_location_type" => "Knock on Door",
                    "address" => $addressTo
                ),
                "transactions" => [ $transactionId ],
                "requested_start_time" => $pickupTimeStart,
                "requested_end_time" => $pickupTimeEnd,
                "is_test" => false
              
        ));
    }

    private function getFromAddress()
    {
        return array(
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
    }

    private function getToAddress()
    {
        return array(
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
    }

    private function getInternationalShipment()
    {
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
        return Shippo_Shipment::create(
            array(
                'address_from'=> Shippo_Address::create(self::getFromAddress()),
                'address_to'=> Shippo_Address::create(self::getToAddress()),
                'parcels'=> array($parcel),
                'async'=> false,
            )
        );
    }
}
