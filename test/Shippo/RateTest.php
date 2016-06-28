<?php

class Shippo_RateTest extends Shippo_Test
{
    
    public function testValidCreate()
    {
        $rate = self::getDefaultRate();
        $this->assertFalse(is_null($rate->results));
    }
    
    public function testListAll()
    {
        $list = Shippo_Rate::all(array(
            'results' => '1',
            'page' => '1'
        ));
        $this->assertFalse(is_null($list->count));
        $this->assertFalse(is_null($list->results));
    }
    
    public function testListPageSize()
    {
        $pagesize = 1;
        $list = Shippo_Rate::all(array(
            'results' => $pagesize,
            'page' => '1'
        ));
        $this->assertEqual(count($list->results), $pagesize);
    }
    
    public static function getDefaultRate()
    {
        parent::setTestApiKey();
        $shipment = Shippo_ShipmentTest::getDefaultShipment();
        try {
            Shippo_Shipment::get_shipping_rates(array(
                'id' => $shipment->object_id,
                'currency' => 'USD'
            ));
        }
        catch (Exception $e) {
            // Expected Exception, rates not ready, prompting to server to generate
        }
        sleep(5);
        return Shippo_Shipment::get_shipping_rates(array(
            'id' => $shipment->object_id,
            'currency' => 'USD'
        ));
    }
}
