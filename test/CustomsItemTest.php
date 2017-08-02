<?php

class Shippo_CustomsItemTest extends TestCase
{
    public function testValidCreate()
    {
        $customsItem = self::getDefaultCustomsItem();
        $this->assertEquals($customsItem->object_state, 'VALID');
    }
    
    public function testInvalidCreate()
    {
        try {
            $customsItem = Shippo_CustomsItem::create(array(
                'invalid_data' => 'invalid'
            ));
        }
        catch (Exception $e) {
            $this->assertTrue(true);
        }
        
    }
    
    public function testRetrieve()
    {
        $customsItem = self::getDefaultCustomsItem();
        $retrieve_customsItem = Shippo_CustomsItem::retrieve($customsItem->object_id);
        $this->assertEquals($retrieve_customsItem->object_id, $customsItem->object_id);
    }
    
    public function testInvalidRetrieve()
    {
        $customsItem = self::getDefaultCustomsItem();
        $retrieve_customsItem = Shippo_CustomsItem::retrieve($customsItem->object_id);
        $this->assertNotEquals($retrieve_customsItem->object_id, 'Invalid Value');
    }
    
    public function testListAll()
    {
        $list = Shippo_CustomsItem::all(array(
            'results' => '3',
            'page' => '1'
        ));
        $this->assertFalse(is_null($list->results));
    }
    
    public function testListPageSize()
    {
        $pagesize = 1;
        $list = Shippo_CustomsItem::all(array(
            'results' => $pagesize,
            'page' => '1'
        ));
        $this->assertEquals(count($list->results), $pagesize);
    }
    
    public static function getDefaultCustomsItem()
    {
        return Shippo_CustomsItem::create(array(
            'description' => 'T-Shirt',
            'quantity' => '2',
            'net_weight' => '400',
            'mass_unit' => 'g',
            'value_amount' => '20',
            'value_currency' => 'USD',
            'tariff_number' => '',
            'origin_country' => 'US',
            'metadata' => 'Order ID #123123'
        ));
    }
}
