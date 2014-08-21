<?php

class Shippo_ParcelTest extends Shippo_Test
{
    public function testValidCreate()
    {
        $parcel = self::getDefaultParcel();
        $this->assertEqual($parcel->object_state, 'VALID');
    }
    
    public function testInvalidCreate()
    {
        try {
            $parcel = Shippo_Parcel::create(array(
                'invalid_data' => 'invalid'
            ));
        }
        catch (Exception $e) {
            $this->pass();
        }
    }
    
    public function testRetrieve()
    {
        $parcel = self::getDefaultParcel();
        $retrieve_parcel = Shippo_Parcel::retrieve($parcel->object_id);
        $this->assertEqual($retrieve_parcel->object_id, $parcel->object_id);
    }
    
    public function testInvalidRetrieve()
    {
        $parcel = self::getDefaultParcel();
        $retrieve_parcel = Shippo_Parcel::retrieve($parcel->object_id);
        $this->assertNotEqual($retrieve_parcel->object_id, 'Invalid Value');
    }
    
    public function testListAll()
    {
        $list = Shippo_Parcel::all(array(
            'results' => '3',
            'page' => '1'
        ));
        $this->assertFalse(is_null($list->count));
        $this->assertFalse(is_null($list->results));
    }
    
    public function testListPageSize()
    {
        $pagesize = 1;
        $list = Shippo_Parcel::all(array(
            'results' => $pagesize,
            'page' => '1'
        ));
        $this->assertEqual(count($list->results), $pagesize);
    }
    
    public static function getDefaultParcel()
    {
        parent::setTestApiKey();
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
