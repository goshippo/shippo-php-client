<?php

class Shippo_TransactionTest extends Shippo_Test
{
    
    public function testValidCreate()
    {
        $transaction = self::getDefaultTransaction();
        $this->assertEqual($transaction->object_state, 'VALID');
    }
    
    public function testInvalidCreate()
    {
        try {
            $transaction = Shippo_Transaction::create(array(
                'invalid_data' => 'invalid'
            ));
        }
        catch (Exception $e) {
            $this->pass();
        }
    }
    
    public function testRetrieve()
    {
        $transaction = self::getDefaultTransaction();
        $retrieve_transaction = Shippo_Transaction::retrieve($transaction->object_id);
        $this->assertEqual($retrieve_transaction->object_id, $transaction->object_id);
    }
    
    public function testInvalidRetrieve()
    {
        $transaction = self::getDefaultTransaction();
        $retrieve_transaction = Shippo_Transaction::retrieve($transaction->object_id);
        $this->assertNotEqual($retrieve_transaction->object_id, 'Invalid Value');
    }
    
    public function testListAll()
    {
        $list = Shippo_Transaction::all(array(
            'results' => '3',
            'page' => '1'
        ));
        $this->assertFalse(is_null($list->count));
        $this->assertFalse(is_null($list->results));
    }
    
    public function testListPageSize()
    {
        $pagesize = 1;
        $list = Shippo_Transaction::all(array(
            'results' => $pagesize,
            'page' => '1'
        ));
        $this->assertEqual(count($list->results), $pagesize);
    }
    
    public static function getDefaultTransaction()
    {
        parent::setTestApiKey();
        $rate = Shippo_RateTest::getDefaultRate();
        return Shippo_Transaction::create(array(
            'rate' => $rate->results[0]->object_id,
            'notification_email_from' => 'true',
            'notification_email_to' => 'false',
            'notification_email_other' => 'max@goshippo.com',
            'metadata' => 'Customer ID 123456'
        ));
    }
}
