<?php

class Shippo_AddressTest extends TestCase
{
    public function testValidCreate()
    {
        $address = self::getDefaultAddress();
        $this->assertEquals($address->is_complete, true);
    }

    public function testResidentialCreate()
    {
        $address = Shippo_Address::create(array(
            'name' => 'John Smith',
            'company' => 'Initech',
            'street1' => 'Greene Rd.',
            'street_no' => '6512',
            'street2' => '',
            'city' => 'Woodridge',
            'state' => 'IL',
            'zip' => '60517',
            'country' => 'US',
            'phone' => '123 353 2345',
            'email' => 'jmercouris@iit.com',
            'metadata' => 'Customer ID 234;234',
            'is_residential' => true
        ));
        $this->assertEquals($address->is_complete, true);
    }
    
    public function testInvalidCreate()
    {
        $address = Shippo_Address::create(array(
            'street1' => 'Greene Rd.',
            'street_no' => '6512',
            'street2' => '',
            'city' => 'Woodridge',
            'state' => 'IL',
            'zip' => '60517',
            'country' => 'US',
            'phone' => '123 353 2345',
            'email' => 'jmercouris@iit.com',
            'metadata' => 'Customer ID 234;234'
        ));
        $this->assertEquals($address->is_complete, false);
    }
    
    public function testRetrieve()
    {
        $address = self::getDefaultAddress();
        $retrieve_address = Shippo_Address::retrieve($address->object_id);
        $this->assertEquals($retrieve_address->object_id, $address->object_id);
    }
    
    public function testInvalidRetrieve()
    {
        $address = self::getDefaultAddress();
        $retrieve_address = Shippo_Address::retrieve($address->object_id);
        $this->assertNotEquals($retrieve_address->object_id, 'Invalid Value');
    }
    
    public function testListAll()
    {
        $list = Shippo_Address::all(array(
            'results' => '3',
            'page' => '1'
        ));
        $this->assertFalse(is_null($list->results));
    }
    
    public function testListPageSize()
    {
        $pagesize = 1;
        $list = Shippo_Address::all(array(
            'results' => $pagesize,
            'page' => '1'
        ));
        $this->assertEquals(count($list->results), $pagesize);
    }
    
    public static function getDefaultAddress()
    {
        return Shippo_Address::create(array(
            'name' => 'John Smith',
            'company' => 'Initech',
            'street1' => 'Greene Rd.',
            'street_no' => '6512',
            'street2' => '',
            'city' => 'Woodridge',
            'state' => 'IL',
            'zip' => '60517',
            'country' => 'US',
            'phone' => '123 353 2345',
            'email' => 'jmercouris@iit.com',
            'metadata' => 'Customer ID 234;234'
        ));
    }
    public static function getDefaultAddress_2()
    {
        return Shippo_Address::create(array(
            'name' => 'John Smith',
            'company' => 'Shippo',
            'street1' => 'Mission St',
            'street_no' => '965',
            'street2' => '',
            'city' => 'San Francisco',
            'state' => 'CA',
            'zip' => '94103',
            'country' => 'US',
            'phone' => '123 353 2345',
            'email' => 'support@goshippo.com',
            'metadata' => 'Customer ID 123;234'
        ));
    }
}
