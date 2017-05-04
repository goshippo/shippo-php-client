<?php

class Shippo_ErrorTest extends TestCase
{
    public function testCreation()
    {
        try {
            throw new Shippo_Error("hello", 500, "{'foo':'bar'}", array(
                'foo' => 'bar'
            ));
            $this->fail("Did not raise error");
        }
        catch (Shippo_Error $e) {
            $this->assertEquals("hello", $e->getMessage());
            $this->assertEquals(500, $e->getHttpStatus());
            $this->assertEquals("{'foo':'bar'}", $e->getHttpBody());
            $this->assertEquals(array(
                'foo' => 'bar'
            ), $e->getJsonBody());
        }
    }
}
