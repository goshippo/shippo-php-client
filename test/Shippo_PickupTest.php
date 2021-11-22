<?php

class Shippo_PickupTest extends TestCase
{
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
        try {
            $pickup = self::getDefaultPickup($transaction->object_id, $rate->carrier_account, $shipment->address_from);
            $this->assertEquals($pickup->status, 'CONFIRMED');
        }
        catch (Exception $e) {
            $jsonBody = (object) $e->jsonBody;
            $this->assertEquals($jsonBody->status, 'ERROR');
            $this->assertEquals(substr($jsonBody->messages[0], 0, 28), 'You have already requested a');
        }        
    }
    
    public function testInvalidCreate()
    {
        try {
            $invalid_account_id = 'XXXd8195e6e8a804f268380e99c9f24XXX';
            $pickup = self::getDefaultPickup($transaction->object_id, $invalid_account_id, $shipment->address_from);
        }
        catch (Exception $e) {
            $this->assertTrue(true);
        }        
    }
    
    private function getDefaultPickup($transactionId, $carrierAccount, $addressFrom)
    {
        $pickupTimeStart = date('Y-m-d', time()).'T'.date('H:i:s', time()).'.000Z';
        $pickupTimeEnd = date('Y-m-d', time() + 60*60*24).'T'.date('H:i:s', time()).'.000Z';
        return Shippo_Pickup::create(array(
            
                "carrier_account" => $carrierAccount,
                "location" => array( 
                    "building_location_type" => "Knock on Door",
                    "address" => self::getFromAddress()
                ),
                "transactions" => array($transactionId),
                "requested_start_time" => $pickupTimeStart,
                "requested_end_time" => $pickupTimeEnd,
                "is_test" => true
        ));
    }

    private function getFromAddress()
    {
        return array(
            "object_purpose"=> "PURCHASE",
            'name' => 'Mr Hippo',
            'company' => 'Shippo',
            'street1' => '85 Caine Avenue',
            'city' => 'San Francisco',
            'state' => 'CA',
            'zip' => '94112',
            'country' => 'US',
            'phone' => '+1 555 341 9393',
            'email' => 'mr-hippo@goshipppo.com',
        );
    }

    private function getToAddress()
    {
        return array(
            "object_purpose"=> "PURCHASE",
            'name' => 'Ms Hippo',
            'company' => "Regent's Park",
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
        $customsDeclaration = Shippo_CustomsDeclaration::create(array(
            'exporter_reference' => '',
            'importer_reference' => '',
            'contents_type' => 'MERCHANDISE',
            'contents_explanation' => 'T-Shirt purchase',
            'invoice' => '#123123',
            'license' => '',
            'certificate' => '',
            'notes' => '',
            'eel_pfc' => 'NOEEI_30_37_a',
            'aes_itn' => '',
            'non_delivery_option' => 'ABANDON',
            'certify' => 'true',
            'certify_signer' => 'Laura Behrens Wu',
            'disclaimer' => '',
            'incoterm' => '',
            'items' => array(array(
                'description' => 'T-Shirt',
                'quantity' => '2',
                'net_weight' => '400',
                'mass_unit' => 'g',
                'value_amount' => '20',
                'value_currency' => 'USD',
                'tariff_number' => '',
                'origin_country' => 'US',
                'metadata' => 'Order ID #123123'
            )),
            'metadata' => 'Order ID #123123'
        ));
        // Example shipment object
        // For complete reference to the shipment object: https://goshippo.com/docs/reference#shipments
        // This object has async=false, indicating that the function will wait until all rates are generated before it returns.
        // By default, Shippo handles responses asynchronously. However this will be depreciated soon. Learn more: https://goshippo.com/docs/async
        return Shippo_Shipment::create(
            array(
                "object_purpose"=> "PURCHASE",
                'address_from'=> Shippo_Address::create(self::getFromAddress()),
                'address_to'=> Shippo_Address::create(self::getToAddress()),
                'parcels'=> array($parcel),
                "customs_declaration"=> $customsDeclaration->object_id,
                'test'=> true,
                'async'=> false,
            )
        );
    }
}
