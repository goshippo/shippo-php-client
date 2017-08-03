<?php

require __DIR__ . '/../vendor/autoload.php';

use Shippo\Shippo_ApiRequestor;
use Shippo\CurlClient;
use Shippo\Shippo;

// Base class for test cases
class TestCase extends \PHPUnit_Framework_TestCase
{
    const SHIPPO_KEY = '<YOUR SHIPPO API KEY>';

    //mock curl client for mocking requests
    private $mock;

    protected function setUp()
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

        Shippo::setApiVersion("2017-03-29");

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
            $this->mock = $this->createMock(CurlClient::class);
            Shippo_ApiRequestor::setHttpClient($this->mock);
        }
        return $this->mock;
    }
    
}
