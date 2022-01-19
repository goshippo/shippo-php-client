<?php

class Shippo_BaseTest extends TestCase
{
    public function testSetApiKey()
    {
        Shippo::setApiKey('dW5pdHRlc3Q6dW5pdHRlc3Q=');
        $this->assertEquals(Shippo::getApiKey(), 'dW5pdHRlc3Q6dW5pdHRlc3Q=');
    }

    public function testSetOAuthTokenApiKey()
    {
        Shippo::setApiKey('oauth.612BUDkTaTuJP3ll5-VkebURXUIJ5Zefxwda1tpd.U_akmGaXVQl80CWPXSbueSG7NX7sNe_HvLJLN1d1pn0=');
        $this->assertEquals(Shippo::getApiKey(), 'oauth.612BUDkTaTuJP3ll5-VkebURXUIJ5Zefxwda1tpd.U_akmGaXVQl80CWPXSbueSG7NX7sNe_HvLJLN1d1pn0=');
    }
}
