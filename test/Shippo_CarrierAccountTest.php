<?php

class Shippo_CarrierAccountTest extends TestCase
{
    
    public function testListAll() {
        $list = Shippo_CarrierAccount::all();
        $this->assertFalse(is_null($list->results));
    }

    public function testRetrieve() {
        $account_id = 'test_account_id';
        $this->mockRequest('GET', '/carrier_accounts/' . $account_id,
            array(), $this->carrierRetrieveResponse($account_id));

        $carrier_account = Shippo_CarrierAccount::retrieve($account_id);
        $this->assertEquals($carrier_account->id, $account_id);
    }

    public function testInvalidRetrieve() {
        $invalid_account_id = 'XXXd8195e6e8a804f268380e99c9f24XXX';
        try {
            Shippo_CarrierAccount::retrieve($invalid_account_id);
            $this->fail('Expected carrier account not found exception to be thrown');
        } catch(Exception $e) {
            $this->assertTrue(strpos(strtolower($e->getMessage()), 'not found') !== false);
        }
    }

    public function testCreate() {
        $carrier_account = $this->createTestAccount();
        $this->assertTrue($carrier_account->test);
        $this->assertEquals($carrier_account->carrier, 'fedex');
    }

    public function testUpdate() {
        $account_id = 'test_account_id';
        $carrier = 'fedex';
        $active = true;
        $this->mockRequest('PUT', '/carrier_accounts/' . $account_id,
            array('active' => $active), $this->carrierUpdateResponse($account_id, $carrier, $active));
        $updated_account = Shippo_CarrierAccount::update(
            $account_id, 
            array('active' => $active)
        );
        $this->assertEquals($updated_account->active, $active);
    }

    private function createTestAccount($account_id=null) {
        if (is_null($account_id)) {
            $account_id = rand();
        }
        $parameters = array(
            'carrier' => 'fedex',
            'account_id' =>  strval($account_id),
            'parameters' => array(
                'meter' => '1234',
            ),
            'test' => true,
            'active' => false
        );
        
        return Shippo_CarrierAccount::create($parameters);
    }

    private function carrierRetrieveResponse($account_id) {
        return array(
            'carrier' => 'usps',
            'object_id' => $account_id,
            'object_owner' => 'happyhippo@goshippo.com',
            'account_id' => 'shippo_usps_account',
            'parameters' => array(
                'is_commercial' => false,
            ),
            'test' => true,
            'active' => true,
            'is_shippo_account' => true,
            'metadata' => ''
        );
    }

    private function carrierUpdateResponse($account_id, $carrier, $params) {
        return array(
            'carrier' => $carrier,
            'object_id' => $account_id,
            'object_owner' => 'happyhippos@goshippo.com',
            'account_id' => 'shippo_usps_account',
            'parameters' => array(
                'is_commercial' => false,
            ),
            'test' => true,
            'active' => true,
            'is_shippo_account' => true,
            'metadata' => ''
        );
    }
}
