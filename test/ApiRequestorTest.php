<?php

class Shippo_ApiRequestorTest extends TestCase
{   
    public function testEncodeObjects()
    {
        // We have to do some work here because this is normally
        // private. This is just for testing! Also it only works on PHP >=
        // 5.3
        if (version_compare(PHP_VERSION, '5.3.2', '>=')) {
            $reflector = new ReflectionClass('Shippo_APIRequestor');
            $method = $reflector->getMethod('_encodeObjects');
            $method->setAccessible(true);
            
            $a = array(
                'customer' => new Shippo_Address('abcd')
            );
            $enc = $method->invoke(null, $a);
            $this->assertEquals($enc, array(
                'customer' => 'abcd'
            ));
            
            // Preserves UTF-8
            $v = array(
                'customer' => "☃"
            );
            $enc = $method->invoke(null, $v);
            $this->assertEquals($enc, $v);
            
            // Encodes latin-1 -> UTF-8
            $v = array(
                'customer' => "\xe9"
            );
            $enc = $method->invoke(null, $v);
            $this->assertEquals($enc, array(
                'customer' => "\xc3\xa9"
            ));
        }
    }

    /**
     * @dataProvider provideValidAPITokens
     *
     * @param $expectedAuthorizationType
     * @param $apiToken
     */
    public function testGetAuthorizationType($expectedAuthorizationType, $apiToken)
    {
        $apiRequestor = new Shippo_ApiRequestor($apiToken);
        $headers = $apiRequestor->getRequestHeaders();
        $authorizationHeader = current(array_filter($headers, function ($header) {
            return strpos($header, 'Authorization:') === 0;
        }));

        $this->assertEquals(strpos($authorizationHeader, 'Authorization: ' . $expectedAuthorizationType), 0);
    }

    public function provideValidAPITokens()
    {
        return [
            'oauth bearer token' => [
                'Bearer',
                'oauth.612BUDkTaTuJP3ll5-VkebURXUIJ5Zefxwda1tpd.U_akmGaXVQl80CWPXSbueSG7NX7sNe_HvLJLN1d1pn0='
            ],
            'random oauth formatted token' => [
                'Bearer',
                'oauth.foo'
            ],
            'shippo token' => [
                'ShippoToken',
                'dW5pdHRlc3Q6dW5pdHRlc3Q='
            ],
            'random token' => [
                'ShippoToken',
                'askdljfgaklsdfjalskdfjalksjd'
            ],
            'random token with oauth in the string' => [
                'ShippoToken',
                'askdljfgaklsdfjalskdfjalksjd.oauth'
            ],
        ];
    }
}
