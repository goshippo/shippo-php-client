<?php

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
