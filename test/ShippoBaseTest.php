<?php

class Shippo_BaseTest extends TestCase
{
    public function testSetApiKey()
    {
        Shippo::setApiKey('dW5pdHRlc3Q6dW5pdHRlc3Q=');
        $this->assertEquals(Shippo::getApiKey(), 'dW5pdHRlc3Q6dW5pdHRlc3Q=');
    }
}