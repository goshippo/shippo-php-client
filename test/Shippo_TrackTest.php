<?php

class Shippo_TrackTest extends TestCase
{
    public function testGet_Status() {
        $tracking_id = '9205590164917312751089';
        $carrier = 'usps';
        $metadata = null;
        $this->mockRequest('GET', '/tracks/' . $carrier . '/' . $tracking_id,
            array(), $this->trackingResponse($tracking_id, $carrier, null));
        $status = Shippo_Track::get_status(array(
            'id' => $tracking_id,
            'carrier' => $carrier
        ));
        $this->assertEquals($status->carrier, $carrier);
        $this->assertEquals($status->tracking_number, $tracking_id);
    }

    public function testCreate() {
        $tracking_id = '9205590164917312751089';
        $carrier = 'usps';
        $metadata= 'test track foo';
        $params = array(
            'carrier' => $carrier,
            'tracking_number' => $tracking_id,
            'metadata' => $metadata
        );
        $this->mockRequest('POST', '/tracks/',
            $params, $this->trackingResponse($tracking_id, $carrier, $metadata));
        $webhook_response = Shippo_Track::create($params);
        $this->assertEquals($webhook_response->carrier, $carrier);
        $this->assertEquals($webhook_response->tracking_number, $tracking_id);
        $this->assertEquals($webhook_response->metadata, $metadata);
    }

    private function trackingResponse($tracking_id, $carrier, $metadata) {
        return array(
            'carrier' => $carrier,
            'tracking_number' => $tracking_id,
            'address_from' => array(
                'city' => 'Las Vegas',
                'state' => 'NV',
                'zip' => '89101',
                'country' => 'US'
            ),
            'address_to' => array(
            'city' => 'Spotsylvania',
            'state' => 'VA',
            'zip' => '22551',
            'country' => 'US'
            ),
            'eta' => '2016-07-23T00:00:00Z',
            'servicelevel' => array(
            'token' => 'usps_priority',
            'name' => 'Priority Mail'
            ),
            'metadata' => $metadata,
            'tracking_status' => array(
                'object_created' => '2016-07-23T20:35:26.129Z',
                'object_updated' => '2016-07-23T20:35:26.129Z',
                'object_id' => 'ce48ff3d52a34e91b77aa98370182624',
                'status' => 'DELIVERED',
                'status_details' => 'Your shipment has been delivered at the destination mailbox.',
                'status_date' => '2016-07-23T13:03:00Z',
                'location' => array(
                    'city' => 'Spotsylvania',
                    'state' => 'VA',
                    'zip' => '22551',
                    'country' => 'US'
                )
            ),
            'tracking_history' => array(
                array(
                    'object_created' => '2016-07-22T14:36:50.943Z',
                    'object_id' => '94490121386241c6b4207bd4b454ec1c',
                    'status' => 'TRANSIT',
                    'status_details' => 'Your shipment has been accepted.',
                    'status_date' => '2016-07-21T15:33:00Z',
                    'location' => array(
                        'city' => 'Las Vegas',
                        'state' => 'NV',
                        'zip' => '89101',
                        'country' => 'US'
                    )
                ),
                array(
                    'object_created' => '2016-07-23T14:35:45.217Z',
                    'object_id' => '6954a2307f97430bb05917d981fd3871',
                    'status' => 'TRANSIT',
                    'status_details' => 'Your shipment has arrived at the post office.',
                    'status_date' => '2016-07-23T05:38:00Z',
                    'location' => array(
                        'city' => 'Spotsylvania',
                        'state' => 'VA',
                        'zip' => '22553',
                        'country' => 'US'
                    )
                )
            )

        );
    }
}
