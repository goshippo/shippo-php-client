<?php

use Shippo\Shippo_Address;
use Shippo\Shippo_InvalidRequestError;

class Shippo_InvalidRequestErrorTest extends TestCase
{
    public function testInvalidObject()
    {
        try {
            Shippo_Address::retrieve('invalid');
        }
        catch (Shippo_InvalidRequestError $e) {
            $this->assertEquals(404, $e->getHttpStatus());
        }
    }
}
