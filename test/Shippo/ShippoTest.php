<?php

class Shippo_Test extends UnitTestCase
{
    function setUp()
    {
        self::setTestApiKey();
    }
    
    // Used to allow classes extending to set their API Key for testing
    public static function setTestApiKey()
    {
        Shippo::setApiKey('dW5pdHRlc3Q6dW5pdHRlc3Q=');
    }
    
    public function testSetApiKey()
    {
        Shippo::setApiKey('dW5pdHRlc3Q6dW5pdHRlc3Q=');
        $this->assertEqual(Shippo::getApiKey(), 'dW5pdHRlc3Q6dW5pdHRlc3Q=');
    }
}
