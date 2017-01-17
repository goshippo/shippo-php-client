<?php

require 'vendor/autoload.php';

// Base class for test cases
class TestCase extends \PHPUnit_Framework_TestCase
{
    const SHIPPO_TOKEN = '924279d5bda97eff28529f264eb14d2646dd3d94';

    //mock curl client for mocking requests
    private $mock;

    protected function setUp()
    {
        //TODO only set api key for api tests
        self::setTestApiKey();
        Shippo_ApiRequestor::setHttpClient(CurlClient::instance());
        $this->mock = null;
    }
    
    // Used to allow classes extending to set their API Key for testing
    protected static function setTestApiKey()
    {
        // Test Shippo API token to be used for testing
        // TODO change to env variable?
        Shippo::setApiKey(self::SHIPPO_TOKEN);
    }

    protected function mockRequest($method, $path, $params = array(), 
        $return = array(), $rcode = 200) 
    {
        $mock = $this->setMockObject();
        $mock->expects($this->any())
             ->method('request')
             ->with(strtolower($method), 'https://api.goshippo.com' . $path,
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
