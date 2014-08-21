<?php

class Shippo_ManifestTest extends Shippo_Test
{
    public function testValidCreate()
    {
        $manifest = self::getDefaultManifest();
        $this->assertEqual($manifest->object_status, 'NOTRANSACTIONS');
    }
    
    public function testInvalidCreate()
    {
        try {
            $manifest = Shippo_Manifest::create(array(
                'invalid_data' => 'invalid'
            ));
        }
        catch (Exception $e) {
            $this->pass();
        }
    }
    
    public function testRetrieve()
    {
        $manifest = self::getDefaultManifest();
        $retrieve_manifest = Shippo_Manifest::retrieve($manifest->object_id);
        $this->assertEqual($retrieve_manifest->object_id, $manifest->object_id);
    }
    
    public function testInvalidRetrieve()
    {
        $manifest = self::getDefaultManifest();
        $retrieve_manifest = Shippo_Manifest::retrieve($manifest->object_id);
        $this->assertNotEqual($retrieve_manifest->object_id, 'Invalid Value');
    }
    
    public function testListAll()
    {
        $list = Shippo_Manifest::all(array(
            'results' => '3',
            'page' => '1'
        ));
        $this->assertFalse(is_null($list->count));
        $this->assertFalse(is_null($list->results));
    }
    
    public function testListPageSize()
    {
        $pagesize = 1;
        $list = Shippo_Manifest::all(array(
            'results' => $pagesize,
            'page' => '1'
        ));
        $this->assertEqual(count($list->results), $pagesize);
    }
    
    public static function getDefaultManifest()
    {
        parent::setTestApiKey();
        $address = Shippo_AddressTest::getDefaultAddress();
        
        return Shippo_Manifest::create(array(
            'provider' => 'USPS',
            'submission_date' => '2014-05-16T23:59:59Z',
            'address_from' => $address->object_id
        ));
    }
}
