<?php

class Shippo_OrderTest extends TestCase
{
    public function testValidCreate()
    {
        $order = self::getDefaultOrder();
        $this->assertNotNull($order->object_id);
    }
    
    public function testInvalidCreate()
    {
        try {
            $order = Shippo_Order::create(array(
                "total_tax" => "0.00",
                "from_address" => array(
                    "city" =>"San Francisco",
                    "state" =>"CA",
                    "object_purpose" =>"PURCHASE",
                    "name" =>"lucas work",
                    "zip" =>"94103",
                    "country" =>"US",
                    "street2" =>"unit 200",
                    "street1" =>"731 Market ST",
                    "company" =>"Shippo",
                    "phone" =>"(985) 580-1234"
                ),
                "shipping_method" => null,
                "weight" => 0,
                "shop_app" =>"Shippo",
                "currency" =>"USD",
                "shipping_cost_currency" =>"USD",
                "shipping_cost" => null,
                "subtotal_price" =>"0",
                "total_price" =>"0",
                "items" => array(
                    array(
                        "total_amount" => 10.45,
                        "weight_unit" => "kg",
                        "title" => "package"
                    )
                ),
                "order_status" =>"PAID",
                "hidden" => false,
                "order_number" =>"LOREM #1",
                "weight_unit" =>"kg",
            ));
        }
        catch (Exception $e) {
            $this->assertTrue(true);
        }
        
    }
    
    public function testRetrieve()
    {
        $order = self::getDefaultOrder();
        $retrieve_order = Shippo_Order::retrieve($order->object_id);
        $this->assertEquals($retrieve_order->object_id, $order->object_id);
    }
    
    public function testInvalidRetrieve()
    {
        $order = self::getDefaultOrder();
        $retrieve_order = Shippo_Order::retrieve($order->object_id);
        $this->assertNotEquals($retrieve_order->object_id, 'Invalid Value');
    }
    
    public function testListAll()
    {
        $list = Shippo_Order::all(array(
            'results' => '3',
            'page' => '1'
        ));
        $this->assertFalse(is_null($list->results));
    }
    
    public function testListPageSize()
    {
        $pagesize = 1;
        $list = Shippo_Order::all(array(
            'results' => $pagesize,
            'page' => '1'
        ));
        $this->assertEquals(count($list->results), $pagesize);
    }
    
    public static function getDefaultOrder()
    {
        return Shippo_Order::create(array(
            "total_tax" => "0.00",
            "from_address" => array(
                "city" =>"San Francisco",
                "state" =>"CA",
                "object_purpose" =>"PURCHASE",
                "name" =>"lucas work",
                "zip" =>"94103",
                "country" =>"US",
                "street2" =>"unit 200",
                "street1" =>"731 Market ST",
                "company" =>"Shippo",
                "phone" =>"(985) 580-1234"
            ),
            "to_address" => array(
                "object_purpose" =>"PURCHASE",
                "name" =>"Mrs. Hippo",
                "company" =>"Shippo & Co",
                "street1" =>"156 Haviland Rd",
                "street2" =>"",
                "city" =>"Ridgefield",
                "state" =>"CT",
                "zip" =>"06877-2822",
                "country" =>"US",
                "phone" =>"+1 555 341 9393",
                "email" =>"support@goshippo.com",
                "metadata" =>"Customer ID 123456"
            ),
            "shipping_method" => null,
            "weight" => 0,
            "shop_app" =>"Shippo",
            "currency" =>"USD",
            "shipping_cost_currency" =>"USD",
            "shipping_cost" => null,
            "subtotal_price" =>"0",
            "total_price" =>"0",
            "items" => array(
                array(
                    "total_amount" => 10.45,
                    "weight_unit" => "kg",
                    "title" => "package"
                )
            ),
            "order_status" =>"PAID",
            "hidden" => false,
            "order_number" =>"LOREM #1",
            "weight_unit" =>"kg",
            "placed_at" =>"2021-11-12T23:59:59"
        ));
    }
}
