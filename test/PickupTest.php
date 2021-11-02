<?php

class Shippo_PickupTest extends TestCase
{
    public function testValidCreate()
    {
        $shipment = Shippo_ShipmentTest::getDefaultShipment();
        $rate = reset($shipment['rates']);
        $transaction = Shippo_Transaction::create(array(
            'rate' => $rate->object_id,
            "async" => false,
            "label_file_type" => "PDF"
        ));
        $pickup = self::getDefaultPickup($transaction->object_id, $rate->carrier_account, $shipment->address_to);
        $retrieve_pickup = Shippo_Address::retrieve($address->object_id);
        $this->assertEquals($pickup->status, 'CONFIRMED');
    }
    
    public function testInvalidCreate()
    {
        try {
            $pickup = self::getDefaultPickup();
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
}
