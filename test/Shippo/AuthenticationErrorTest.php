<?php

class Shippo_AuthenticationErrorTest extends UnitTestCase
{
    public function testInvalidCredentials()
    {
        //    $this->expectException(); 
        Shippo::setApiKey('invalid');
        try {
            $address = Shippo_Address::create();
        }
        catch (Shippo_AuthenticationError $e) {
            $this->assertEqual(401, $e->getHttpStatus());
        }
    }
}
