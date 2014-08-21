<?php

class Shippo_InvalidRequestErrorTest extends UnitTestCase
{
    public function testInvalidObject()
    {
        try {
            Shippo_Address::retrieve('invalid');
        }
        catch (Shippo_InvalidRequestError $e) {
            $this->assertEqual(404, $e->getHttpStatus());
        }
    }
}
