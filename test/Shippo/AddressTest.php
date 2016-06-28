<?php

class Shippo_AddressTest extends Shippo_Test
{
    public function testValidCreate()
    {
        $address = self::getDefaultAddress();
        $this->assertEqual($address->object_state, 'VALID');
    }

    public function testResidentialCreate()
    {
        $address = Shippo_Address::create(array(
            'object_purpose' => 'PURCHASE',
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
        $this->assertEqual($address->object_state, 'VALID');
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
        $this->assertEqual($address->object_state, 'INCOMPLETE');
    }
    
    public function testRetrieve()
    {
        $address = self::getDefaultAddress();
        $retrieve_address = Shippo_Address::retrieve($address->object_id);
        $this->assertEqual($retrieve_address->object_id, $address->object_id);
    }
    
    public function testInvalidRetrieve()
    {
        $address = self::getDefaultAddress();
        $retrieve_address = Shippo_Address::retrieve($address->object_id);
        $this->assertNotEqual($retrieve_address->object_id, 'Invalid Value');
    }
    
    public function testListAll()
    {
        $list = Shippo_Address::all(array(
            'results' => '3',
            'page' => '1'
        ));
        $this->assertFalse(is_null($list->count));
        $this->assertFalse(is_null($list->results));
    }
    
    public function testListPageSize()
    {
        $pagesize = 1;
        $list = Shippo_Address::all(array(
            'results' => $pagesize,
            'page' => '1'
        ));
        $this->assertEqual(count($list->results), $pagesize);
    }
    
    public static function getDefaultAddress()
    {
        parent::setTestApiKey();
        return Shippo_Address::create(array(
            'object_purpose' => 'QUOTE',
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
}
