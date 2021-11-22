<?php

class Shippo_ParcelTest extends TestCase
{
    public function testValidCreate()
    {
        $parcel = self::getDefaultParcel();
        $this->assertEquals($parcel->object_state, 'VALID');
    }
    
    public function testInvalidCreate()
    {
        try {
            $parcel = Shippo_Parcel::create(array(
                'invalid_data' => 'invalid'
            ));
        }
        catch (Exception $e) {
            $this->assertTrue(true);
        }
    }
    
    public function testRetrieve()
    {
        $parcel = self::getDefaultParcel();
        $retrieve_parcel = Shippo_Parcel::retrieve($parcel->object_id);
        $this->assertEquals($retrieve_parcel->object_id, $parcel->object_id);
    }
    
    public function testInvalidRetrieve()
    {
        $parcel = self::getDefaultParcel();
        $retrieve_parcel = Shippo_Parcel::retrieve($parcel->object_id);
        $this->assertNotEquals($retrieve_parcel->object_id, 'Invalid Value');
    }
    
    public function testListAll()
    {
        $list = Shippo_Parcel::all(array(
            'results' => '3',
            'page' => '1'
        ));
        $this->assertFalse(is_null($list->results));
    }
    
    public function testListPageSize()
    {
        $pagesize = 2;
        $list = Shippo_Parcel::all(array(
            'results' => $pagesize,
            'page' => '1'
        ));
        $this->assertEquals(count($list->results), $pagesize);
    }
    
    public static function getDefaultParcel()
    {
        return Shippo_Parcel::create(array(
            'length' => '5',
            'width' => '5',
            'height' => '5',
            'distance_unit' => 'cm',
            'weight' => '2',
            'mass_unit' => 'lb',
            'template' => '',
            'metadata' => 'Customer ID 123456'
        ));
    }
}
