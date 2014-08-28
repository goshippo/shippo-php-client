<?php

class Shippo_ShipmentTest extends Shippo_Test
{
    
    public function testValidCreate()
    {
        $shipment = self::getDefaultShipment();
        $this->assertEqual($shipment->object_state, 'VALID');
    }
    
    public function testInvalidCreate()
    {
        try {
            $shipment = Shippo_Shipment::create(array(
                'invalid_data' => 'invalid'
            ));
        }
        catch (Exception $e) {
            $this->pass();
        }
    }
    
    public function testRetrieve()
    {
        $shipment = self::getDefaultShipment();
        $retrieve_shipment = Shippo_Shipment::retrieve($shipment->object_id);
        $this->assertEqual($retrieve_shipment->object_id, $shipment->object_id);
    }
    
    public function testInvalidRetrieve()
    {
        $shipment = self::getDefaultShipment();
        $retrieve_shipment = Shippo_Shipment::retrieve($shipment->object_id);
        $this->assertNotEqual($retrieve_shipment->object_id, 'Invalid Value');
    }
    
    public function testListAll()
    {
        $list = Shippo_Shipment::all(array(
            'results' => '3',
            'page' => '1'
        ));
        $this->assertFalse(is_null($list->count));
        $this->assertFalse(is_null($list->results));
    }
    
    public function testListPageSize()
    {
        $pagesize = 1;
        $list = Shippo_Shipment::all(array(
            'results' => $pagesize,
            'page' => '1'
        ));
        $this->assertEqual(count($list->results), $pagesize);
    }
    
    public static function getDefaultShipment()
    {
        parent::setTestApiKey();
        $addressFrom = Shippo_AddressTest::getDefaultAddress();
        $addressTo = Shippo_AddressTest::getDefaultAddress();
        $parcel = Shippo_ParcelTest::getDefaultParcel();
        return Shippo_Shipment::create(array(
            'object_purpose' => 'QUOTE',
            'address_from' => $addressFrom->object_id,
            'address_to' => $addressTo->object_id,
            'parcel' => $parcel->object_id,
            'submission_type' => 'PICKUP',
            'submission_date' => '2013-12-03T12:00:00.000Z',
            'insurance_amount' => '30',
            'insurance_currency' => 'USD',
            'extra' => '{signature_confirmation: true}',
            'customs_declaration' => '',
            'reference_1' => '',
            'reference_2' => '',
            'metadata' => 'Customer ID 123456'
        ));
    }
}
