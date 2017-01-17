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
    
}
