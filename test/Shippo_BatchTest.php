<?php

class Shippo_BatchTest extends TestCase
{
    public function testCreate() {
        $carrier = 'test carrier account';
        $metadata = 'test metadata';
        $data = array(
            'default_carrier_account' => $carrier,
            'default_servicelevel_token' => 'usps_priority',
            'label_filetype' => 'PDF_4x6',
            'metadata' => $metadata,
            'batch_shipments' => array(
                array(
                    'shipment' => array(    
                        'address_from' => array(
                            'name' => 'Mr Hippo',
                            'street1' => '965 Mission St',
                            'street2' => 'Ste 201',
                            'city' => 'San Francisco',
                            'state' => 'CA',
                            'zip' => '94103',
                            'country' => 'US',
                            'phone' => '4151234567',
                            'email' => 'mrhippo@goshippo.com'
                        ),
                        'address_to' => array(
                            'name' => 'Mrs Hippo',
                            'company' => '',
                            'street1' => 'Broadway 1',
                            'street2' => '',
                            'city' => 'New York',
                            'state' => 'NY',
                            'zip' => '10007',
                            'country' => 'US',
                            'phone' => '4151234567',
                            'email' => 'mrshippo@goshippo.com'
                        ),
                        'parcels' => array(
                            array(
                                'length' => '5',
                                'width' => '5',
                                'height' => '5',
                                'distance_unit' => 'in',
                                'weight' => '2',
                                'mass_unit' => 'oz'
                            )
                        )
                    )
                )
            ) 
        );

        $this->mockRequest('POST', '/batches/',
            $data, $this->batchCreateResponse($carrier, $metadata));
        $batch = Shippo_Batch::create($data);
        $this->assertEquals($batch->default_carrier_account, $carrier);
        $this->assertEquals($batch->metadata, $metadata);
    }

    public function testRetrieve() {
        $batch_id = 'test_batch_id';
        $this->mockRequest('GET', '/batches/' . $batch_id,
            array(), $this->batchRetrieveResponse($batch_id));
        $batch = Shippo_Batch::retrieve($batch_id);
        $this->assertEquals($batch->object_id, $batch_id);
    }

    public function testAdd() {
        $batch_id = 'test_batch_id';
        $data = array(
            array('shipment' => 'batchID1'),
            array('shipment' => 'batchID2'),
            array('shipment' => 'batchID3')
        );
        $this->mockRequest('POST', '/batches/' . $batch_id . '/add_shipments/',
            $data, $this->batchAddResponse($batch_id));
        $batch = Shippo_Batch::add($batch_id, $data);
        $this->assertEquals($batch->object_id, $batch_id);
    }

    public function testRemove() {
        $batch_id = 'test_batch_id';
        $data = array(
            "batchID1",
            "batchID2"
        );
        $this->mockRequest('POST', '/batches/' . $batch_id . '/remove_shipments/',
            $data, $this->batchRemoveResponse($batch_id));
        $batch = Shippo_Batch::remove($batch_id, $data);
        $this->assertEquals($batch->object_id, $batch_id);
    }

    public function testPurchase() {
        $batch_id = 'test_batch_id';
        $this->mockRequest('POST', '/batches/' . $batch_id . '/purchase/',
            array(), $this->batchPurchaseResponse($batch_id));
        $batch = Shippo_Batch::purchase($batch_id);
        $this->assertEquals($batch->object_id, $batch_id);
    }

    private function batchCreateResponse($carrier, $metadata) {
        return array(
            'object_id' => 'a015eb693cca465dbb6523ce6d2e3c65',
            'object_owner' => 'admin',
            'status' => 'VALIDATING',
            'object_created' => '2016-09-12T15:25:43.465Z',
            'object_updated' => '2016-09-12T15:25:43.465Z',
            'metadata' => $metadata,
            'default_carrier_account' => $carrier,
            'default_servicelevel_token' => 'usps_priority',
            'label_filetype' => 'PDF_4x6',
            'batch_shipments' => array(
                'next' => null,
                'previous' => null,
                'results' => array()
            ),
            'object_results' => array(
                'purchase_succeeded' => 0,
                'purchase_failed' => 0,
                'creation_failed' => 0,
                'creation_succeeded' => 0
            ),
            'label_url' => array()
        );
    }

    private function batchRetrieveResponse($batch_id) {
        return array(
            'object_id' => $batch_id,
            'object_owner' => 'shippo@goshippo.com',
            'status' => 'INVALID',
            'object_created' => '2016-01-04T00:15:44.394Z',
            'object_updated' => '2016-01-04T00:48:13.841Z',
            'metadata' => '',
            'default_carrier_account' => 'a4391cd4ab974f478f55dc08b5c8e3b3',
            'default_servicelevel_token' => 'usps_priority',
            'label_filetype' => 'PDF_4x6',
            'batch_shipments' => array(
                'next' => 'https://api.goshippo.com/batches/5ef63c54f5bf45d3b1f8fb37dcb1c5f4?object_results=creation_failed&page=3',
                'previous' => 'https://api.goshippo.com/batches/5ef63c54f5bf45d3b1f8fb37dcb1c5f4?object_results=creation_failed&page=1',
                'results' => array(
                    array(
                    'metadata' => '',
                    'carrier_account' => 'a4391cd4ab974f478f55dc08b5c8e3b3',
                    'servicelevel_token' => 'fedex_ground',
                    'shipment' => null,
                    'transaction' => null,
                    'object_id' => 'e11c95a6788d4ddcaa22f03175838740',
                    'status' => 'INVALID',
                    'messages' => array(
                        array(
                            array(
                                    'address_to' => array(
                                        'This field is required.'
                                    )
                                )
                            )
                        )
                    )
                )
            ),
            'object_results' => array(
                'purchase_succeeded' => 0,
                'purchase_failed' => 0,
                'creation_failed' => 3,
                'creation_succeeded' => 5
            ),
            'label_url' => array()
        );
    }

    private function batchAddResponse($batch_id) {
        return array(
            'object_id' => $batch_id,
            'object_owner' => 'shippo@goshippo.com',
            'status' => 'VALID',
            'object_created' => '2016-01-04T00:15:44.394Z',
            'object_updated' => '2016-01-04T00:48:13.841Z',
            'metadata' => '',
            'default_carrier_account' => 'a4391cd4ab974f478f55dc08b5c8e3b3',
            'default_servicelevel_token' => 'usps_priority',
            'label_filetype' => 'PDF_4x6',
            'batch_shipments' => array(
                'next' => null,
                'previous' => null,
                'results' => array(
                    array(
                        'metadata' => 'add batchshipment1',
                        'carrier_account' => 'e11c95a6788d4ddcaa22f03175838740',
                        'servicelevel_token' => 'fedex_2_day',
                        'shipment' => '99c326a150f54e638b08623833ef152f',
                        'transaction' => null,
                        'object_id' => 'aa7dea463a5a48b0b8fb21f90e72d779',
                        'status' => 'VALID',
                        'messages' => array()
                    ),
                    array(
                        'metadata' => 'add batchshipment2',
                        'carrier_account' => 'd2ce085dd3734a22b20c6df36a63aa07',
                        'servicelevel_token' => 'ups_ground',
                        'shipment' => '2ba26e9733954b3fb8fef38fbb742676',
                        'transaction' => null,
                        'object_id' => 'f11b46440c144ce3b97fb5ddf02b8c71',
                        'status' => 'VALID',
                        'messages' => array()
                    )
                )
            ),
            'object_results' => array(
                'purchase_succeeded' => 0,
                'purchase_failed' => 0,
                'creation_failed' => 0,
                'creation_succeeded' => 4
            ),
            'label_url' => array()
        );
    }

    private function batchRemoveResponse($batch_id) {
        return array(
            'object_id' => $batch_id,
            'object_owner' => 'shippo@goshippo.com',
            'status' => 'VALID',
            'object_created' => '2016-01-04T00:15:44.394Z',
            'object_updated' => '2016-01-04T00:48:13.841Z',
            'metadata' => '',
            'default_carrier_account' => 'a4391cd4ab974f478f55dc08b5c8e3b3',
            'default_servicelevel_token' => 'usps_priority',
            'label_filetype' => 'PDF_4x6',
            'batch_shipments' => array(
                'next' => null,
                'previous' => null,
                'results' => array()
            ),
            'object_results' => array(
                'purchase_succeeded' => 0,
                'purchase_failed' => 0,
                'creation_failed' => 0,
                'creation_succeeded' => 0
            ),
            'label_url' => array()
        );
    }

    private function batchPurchaseResponse($batch_id) {
        return array(
            'object_id' => $batch_id,
            'object_owner' => 'shippo@goshippo.com',
            'status' => 'PURCHASING',
            'object_created' => '2016-01-04T00:15:44.394Z',
            'object_updated' => '2016-01-04T00:48:13.841Z',
            'metadata' => '',
            'default_carrier_account' => 'a4391cd4ab974f478f55dc08b5c8e3b3',
            'default_servicelevel_token' => 'usps_priority',
            'label_filetype' => 'PDF_4x6',
            'batch_shipments' => array(
                'next' => null,
                'previous' => null,
                'results' => array(
                    array(
                        'metadata' => '',
                        'carrier_account' => null,
                        'servicelevel_token' => null,
                        'shipment' => '77fd9aeaf9b347da9aa95eb250997dc3',
                        'transaction' => null,
                        'object_id' => '2ab2b452392545908d2cef8861a39c35',
                        'status' => 'VALID',
                        'messages' => array()
                    )
                )
            ),
            'object_results' => array(
                'purchase_succeeded' => 1,
                'purchase_failed' => 0,
                'creation_failed' => 0,
                'creation_succeeded' => 4
            ),
            'label_url' => array()
        );
    }
}
