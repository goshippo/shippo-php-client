<?php

require 'vendor/autoload.php';

// Base class for test cases
class TestCase extends \PHPUnit\Framework\TestCase
{
    const SHIPPO_KEY = '<YOUR SHIPPO API KEY>';

    //mock curl client for mocking requests
    private $mock;

    protected function setUp() : void
    {
        self::authFromEnv();
        Shippo_ApiRequestor::setHttpClient(CurlClient::instance());
        $this->mock = null;
    }

    protected static function authFromEnv()
    {
        $apiKey = getenv('SHIPPO_API_KEY');
        if (!$apiKey) {
            $apiKey = self::SHIPPO_KEY;
        }

        Shippo::setApiKey($apiKey);
    }

    protected function mockRequest($method, $path, $params = array(), 
        $return = array(), $rcode = 200) 
    {
        $mock = $this->setMockObject();
        $mock->expects($this->any())
             ->method('request')
             ->with(strtolower($method), Shippo::$apiBase . $path,
                $this->anything(), $params)
             ->willReturn(array(json_encode($return), $rcode));

    }

    protected function setMockObject()
    {
        if (!$this->mock) {
            $this->mock = $this->createMock('CurlClient');
            Shippo_ApiRequestor::setHttpClient($this->mock);
        }
        return $this->mock;
    }
    
}
