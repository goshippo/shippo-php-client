<?php

class Shippo_UtilTest extends UnitTestCase
{
    public function testIsList()
    {
        $list = array(
            5,
            'nstaoush',
            array()
        );
        $this->assertTrue(Shippo_Util::isList($list));
        
        $notlist = array(
            5,
            'nstaoush',
            array(),
            'bar' => 'baz'
        );
        $this->assertFalse(Shippo_Util::isList($notlist));
    }
    
    public function testThatPHPHasValueSemanticsForArrays()
    {
        $original = array(
            'php-arrays' => 'value-semantics'
        );
        $derived = $original;
        $derived['php-arrays'] = 'reference-semantics';
        
        $this->assertEqual('value-semantics', $original['php-arrays']);
    }
}
