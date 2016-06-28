<?php

class Shippo_CarrierAccountTest extends Shippo_Test
{
    
    public function testListAll() {
        $list = Shippo_CarrierAccount::all();
        $this->assertFalse(is_null($list->count));
        $this->assertFalse(is_null($list->results));
    }
	
	public function testRetrieve() {
		$account_id = 'd8195e6e8a804f268380e99c9f2488b1';
		$carrier_account = Shippo_CarrierAccount::retrieve($account_id);
		$this->assertEqual($carrier_account->id, $account_id);
	}
    
	public function testInvalidRetrieve() {
		$invalid_account_id = 'XXXd8195e6e8a804f268380e99c9f24XXX';
		try {
			Shippo_CarrierAccount::retrieve($invalid_account_id);
			$this->fail('Expected carrier account not found exception to be thrown');
		} catch(Exception $e) {
			$this->assertTrue(strpos($e->getMessage(), 'Not Found') !== false);
		}
	}
	
	public function testCreate() {
		$carrier_account = $this->createTestAccount();
		$this->assertTrue($carrier_account->parameters->test);
		$this->assertEqual($carrier_account->carrier, 'fedex');
	}
	
	public function testUpdate() {
		$carrier_account = $this->createTestAccount();
		$this->assertTrue($carrier_account->parameters->test);
		$this->assertEqual($carrier_account->carrier, 'fedex');
		
		// update test account
		$current_account_id = $carrier_account->account_id;
		$new_account_id = $current_account_id . '_updated_account_id';
		$updated_account = Shippo_CarrierAccount::update(
			$carrier_account->object_id, 
			array('account_id' => $new_account_id)
		);
		
		$this->assertEqual($updated_account->account_id, $new_account_id);
	}
	
	private function createTestAccount($account_id=null) {
		if (is_null($account_id)) {
			$account_id = rand();
		}
		$parameters = array(
			'carrier' => 'fedex',
			'account_id' => $account_id,
			'parameters' => array('meter' => '1234', 'test' => true)
		);
		
		return Shippo_CarrierAccount::create($parameters);
	}
}
