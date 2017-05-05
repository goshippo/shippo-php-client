<?php

use Shippo\Shippo;
use Shippo\Shippo_Address;
use Shippo\Shippo_AuthenticationError;

class Shippo_AuthenticationErrorTest extends TestCase
{
    public function testInvalidCredentials()
    {
        //    $this->expectException(); 
        Shippo::setApiKey('invalid');
        try {
            $address = Shippo_Address::create();
        }
        catch (Shippo_AuthenticationError $e) {
            $this->assertEquals(401, $e->getHttpStatus());
        }
    }
}
