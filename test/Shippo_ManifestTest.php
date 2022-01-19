<?php

class Shippo_ManifestTest extends TestCase
{
    public function testCreate()
    {
        $carrier_account = 'test carrier account id';
        $shipment_date = '2014-05-16T23:59:59Z';
        $data = array(
            'carrier_account' => $carrier_account,
            'shipment_date' => $shipment_date,
            'address_from' => 'd799c2679e644279b59fe661ac8fa488',
            'transactions' => array('64bba01845ef40d29374032599f22588', 'c169aa586a844cc49da00d0272b590e1'),
            'async' => false
        );
        $this->mockRequest('POST', '/manifests/',
            $data, $this->manifestCreateResponse($carrier_account, $shipment_date));
        $manifest = Shippo_Manifest::create($data);
        $this->assertEquals($manifest->carrier_account, $carrier_account);
        $this->assertEquals($manifest->shipment_date, $shipment_date);
    }
    
    public function testRetrieve()
    {
        $manifest_id = '0fadebf6f60c4aca95fa01bcc59c79ae';
        $this->mockRequest('GET', '/manifests/' . $manifest_id,
            array(), $this->manifestRetrieveResponse($manifest_id));
        $retrieve_manifest = Shippo_Manifest::retrieve($manifest_id);
        $this->assertEquals($retrieve_manifest->object_id, $manifest_id);
    }
    
    public function testListAll()
    {
        $list = Shippo_Manifest::all(array(
            'results' => '3',
            'page' => '1'
        ));
        $this->assertFalse(is_null($list->results));
    }

    private function manifestCreateResponse($carrier_account, $shipment_date)
    {
        return array(
            'object_created' => '2014-05-16T03:43:52.765Z',
            'object_updated' => '2014-05-16T03:43:55.445Z',
            'object_id' => '0fadebf6f60c4aca95fa01bcc59c79ae',
            'object_owner' => 'mrhippo@goshippo.com',
            'status' => 'SUCCESS',
            'carrier_account' => $carrier_account,
            'shipment_date' => $shipment_date,
            'address_from' => 'd799c2679e644279b59fe661ac8fa488',
            'transactions' => array('64bba01845ef40d29374032599f22588', 'c169aa586a844cc49da00d0272b590e1'),
            'documents' => array(
                'https://shippo-delivery.s3.amazonaws.com/0fadebf6f60c4aca95fa01bcc59c79ae.pdf?Signature=tlQU3RECwdHUQJQadwqg5bAzGFQ%3D&Expires=1402803835&AWSAccessKeyId=AKIAJTHP3LLFMYAWALIA'
            )
        );
    }

    private function manifestRetrieveResponse($manifest_id)
    {
        return array(
           'object_created' =>'2014-05-16T03:43:52.765Z',
           'object_updated' =>'2014-05-16T03:43:55.445Z',
           'object_id' => $manifest_id,
           'object_owner' =>'mrhippo@goshippo.com',
           'status' =>'SUCCESS',
           'carrier_account' => 'b741b99f95e841639b54272834bc478c',
           'shipment_date' =>'2014-05-16T23:59:59Z',
           'address_from' =>'008ee72b723c4f129371b7346fe2f55f',
           'transactions' => array('64bba01845ef40d29374032599f22588', 'c169aa586a844cc49da00d0272b590e1'),
           'documents' => array(
              'https://shippo-delivery.s3.amazonaws.com/0fadebf6f60c4aca95fa01bcc59c79ae.pdf?Signature=tlQU3RECwdHUQJQadwqg5bAzGFQ%3D&Expires=1402803835&AWSAccessKeyId=AKIAJTHP3LLFMYAWALIA'
           )
        );
    }
}
