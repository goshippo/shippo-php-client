<?php

class Shippo_CustomsDeclarationTest extends Shippo_Test
{
    public function testValidCreate()
    {
        $customsDeclaration = self::getDefaultCustomsDeclaration();
        $this->assertEqual($customsDeclaration->object_state, 'VALID');
    }
    
    public function testInvalidCreate()
    {
        try {
            $customsDeclaration = Shippo_CustomsDeclaration::create(array(
                'invalid_data' => 'invalid'
            ));
        }
        catch (Exception $e) {
            $this->pass();
        }
        
    }
    
    public function testRetrieve()
    {
        $customsDeclaration = self::getDefaultCustomsDeclaration();
        $retrieve_customsDeclaration = Shippo_CustomsDeclaration::retrieve($customsDeclaration->object_id);
        $this->assertEqual($retrieve_customsDeclaration->object_id, $customsDeclaration->object_id);
    }
    
    public function testInvalidRetrieve()
    {
        $customsDeclaration = self::getDefaultCustomsDeclaration();
        $retrieve_customsDeclaration = Shippo_CustomsDeclaration::retrieve($customsDeclaration->object_id);
        $this->assertNotEqual($retrieve_customsDeclaration->object_id, 'Invalid Value');
    }
    
    public function testListAll()
    {
        $list = Shippo_CustomsDeclaration::all(array(
            'results' => '3',
            'page' => '1'
        ));
        $this->assertFalse(is_null($list->count));
        $this->assertFalse(is_null($list->results));
    }
    
    public function testListPageSize()
    {
        $pagesize = 1;
        $list = Shippo_CustomsDeclaration::all(array(
            'results' => $pagesize,
            'page' => '1'
        ));
        $this->assertEqual(count($list->results), $pagesize);
    }
    
    public static function getDefaultCustomsDeclaration()
    {
        parent::setTestApiKey();
        $customsItem = Shippo_CustomsItemTest::getDefaultCustomsItem();
        return Shippo_CustomsDeclaration::create(array(
            'exporter_reference' => '',
            'importer_reference' => '',
            'contents_type' => 'MERCHANDISE',
            'contents_explanation' => 'T-Shirt purchase',
            'invoice' => '#123123',
            'license' => '',
            'certificate' => '',
            'notes' => '',
            'eel_pfc' => 'NOEEI_30_37_a',
            'aes_itn' => '',
            'non_delivery_option' => 'ABANDON',
            'certify' => 'true',
            'certify_signer' => 'Laura Behrens Wu',
            'disclaimer' => '',
            'incoterm' => '',
            'items' => array(
                $customsItem->object_id
            ),
            'metadata' => 'Order ID #123123'
        ));
    }
}
