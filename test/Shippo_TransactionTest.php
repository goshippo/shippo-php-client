<?php

class Shippo_TransactionTest extends TestCase
{
    
    public function testCreate()
    {
        $rate = 'test_rate_id';
        $metadata = 'metadata foo';
        $data = array(
            'rate' => $rate,
            'metadata' => $metadata
        );
        $this->mockRequest('POST', '/transactions/',
            $data, $this->transactionCreateResponse($rate, $metadata));
        $transaction = Shippo_Transaction::create($data);

        $this->assertEquals($transaction->rate, $rate);
        $this->assertEquals($transaction->metadata, $metadata);
    }
    
    public function testRetrieve()
    {
        $transaction_id = '70ae8117ee1749e393f249d5b77c45e0';
         $this->mockRequest('GET', '/transactions/' . $transaction_id,
            array(), $this->transactionRetrieveResponse($transaction_id));
        $retrieve_transaction = Shippo_Transaction::retrieve($transaction_id);
        $this->assertEquals($retrieve_transaction->object_id, $transaction_id);
    }

    public function testListAll()
    {
        $list = Shippo_Transaction::all(array(
            'results' => '3',
            'page' => '1'
        ));
        $this->assertFalse(is_null($list->results));
    }
    
    public function testListPageSize()
    {
        $pagesize = 1;
        $list = Shippo_Transaction::all(array(
            'results' => $pagesize,
            'page' => '1'
        ));
        $this->assertEquals(count($list->results), $pagesize);
    }

    private function transactionCreateResponse($rate, $metadata)
    {
        return array(
           'object_state' => 'VALID',
           'status' => 'SUCCESS',
           'object_created' => '2014-07-25T02:09:34.422Z',
           'object_updated' => '2014-07-25T02:09:34.513Z',
           'object_id' => 'ef8808606f4241ee848aa5990a09933c',
           'object_owner' => 'shippotle@goshippo.com',
           'test' => true,
           'rate' => $rate,
           'tracking_number' => '',
           'tracking_status' => null,
           'tracking_url_provider' => '',
           'label_url' => '',
           'commercial_invoice_url' => '',
           'messages' => array(),
           'metadata' => $metadata
        );
    }

    private function transactionRetrieveResponse($transaction_id)
    {
        return array(
           'object_state' => 'VALID',
           'status' => 'SUCCESS',
           'object_created' => '2014-07-17T00:43:40.842Z',
           'object_updated' => '2014-07-17T00:43:50.531Z',
           'object_id' => $transaction_id,
           'object_owner' => 'shippotle@goshippo.com',
           'test' => true,
           'rate' => 'ee81fab0372e419ab52245c8952ccaeb',
           'tracking_number' => '9499907123456123456781',
           'tracking_status' => array(
              'object_created' => '2014-07-17T00:43:50.402Z',
              'object_id' => '907d5e6120ed491ea27d4f681a7ccd4d',
              'status' => 'UNKNOWN',
              'status_details' => '',
              'status_date' => null
           ),
           'tracking_history' => array(
              array(
                 'object_created' => '2014-07-17T00:43:50.402Z',
                 'object_id' =>'907d5e6120ed491ea27d4f681a7ccd4d',
                 'status' =>'UNKNOWN',
                 'status_details' =>'',
                 'status_date' =>null
              ),
           ),
           'tracking_url_provider' =>'https://tools.usps.com/go/TrackConfirmAction_input?origTrackNum=9499907123456123456781',
           'label_url' =>'https://shippo-delivery.s3.amazonaws.com/70ae8117ee1749e393f249d5b77c45e0.pdf?Signature=vDw1ltcyGveVR1OQoUDdzC43BY8%3D&Expires=1437093830&AWSAccessKeyId=AKIAJTHP3LLFMYAWALIA',
           'commercial_invoice_url' => '',
           'messages' =>array(),
           'metadata' =>''
        );
    }
}
