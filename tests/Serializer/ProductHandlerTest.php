<?php
namespace App\Tests\Serializer;

use App\Serializer\ProductHandler;
use App\Entity\Discount;
use App\Entity\Price;
use App\Entity\Product;

use JMS\Serializer\GraphNavigator;
use JMS\Serializer\JsonSerializationVisitor;
use JMS\Serializer\JsonDeserializationVisitor;
use JMS\Serializer\Context;

use PHPUnit\Framework\TestCase;

class ProductHandlerTest extends TestCase
{
    private $productHandler;
    private $serializationVisitor;
    private $context;

    protected function setUp() 
    {
        $this->productHandler = new ProductHandler();
        $this->serializationVisitor = new JsonSerializationVisitor();

        $this->context = new class extends Context {
             public function getDepth() : int { return 0; }
             public function getDirection() : int { return 0; }
        };
    }

    public function testSerializeProductToJSONNoDiscount()
    {
        $price = new Price(1000);
        $product = new Product('Product 1', $price);

        $data = $this->productHandler->serializeProductToJson($this->serializationVisitor, $product, [], $this->context);

        $this->assertArrayHasKey('name', $data); 
        $this->assertEquals('Product 1', $data['name']);
        $this->assertArrayHasKey('price', $data);
        $this->assertArrayHasKey('amount', $data['price']);
        $this->assertEquals(10.00, $data['price']['amount']);
        $this->assertArrayHasKey('final_price', $data['price']);
        $this->assertEquals(10.00, $data['price']['final_price']);
        $this->assertArrayNotHasKey('discount_amount', $data['price']);
        $this->assertArrayNotHasKey('discount_type', $data['price']);
    }

    public function testSerializeProductToJSONWithDiscountPercentual()
    {
        $discount = new Discount(1000, Discount::PERCENTUAL);
        $price = new Price(1000, $discount);
        
        $product = new Product('Product 2', $price);

        $data = $this->productHandler->serializeProductToJson($this->serializationVisitor, $product, [], $this->context);

        $this->assertArrayHasKey('name', $data); 
        $this->assertEquals('Product 2', $data['name']);
        $this->assertArrayHasKey('price', $data);
        $this->assertArrayHasKey('amount', $data['price']);
        $this->assertEquals(10.00, $data['price']['amount']);
        $this->assertArrayHasKey('final_price', $data['price']);
        $this->assertEquals(9.00, $data['price']['final_price']);
        $this->assertArrayHasKey('discount_amount', $data['price']);
        $this->assertEquals(10.00, $data['price']['discount_amount']);
        $this->assertArrayHasKey('discount_type', $data['price']);
        $this->assertEquals(Discount::PERCENTUAL, $data['price']['discount_type']);

    }

    public function testSerializeProductToJSONWithDiscountConcrete()
    {
        $discount = new Discount(200, Discount::CONCRETE);
        $price = new Price(1000, $discount);
        
        $product = new Product('Product 3', $price);

        $data = $this->productHandler->serializeProductToJson($this->serializationVisitor, $product, [], $this->context);

        $this->assertArrayHasKey('name', $data); 
        $this->assertEquals('Product 3', $data['name']);
        $this->assertArrayHasKey('price', $data);
        $this->assertArrayHasKey('amount', $data['price']);
        $this->assertEquals(10.00, $data['price']['amount']);
        $this->assertArrayHasKey('final_price', $data['price']);
        $this->assertEquals(8.00, $data['price']['final_price']);
        $this->assertArrayHasKey('discount_amount', $data['price']);
        $this->assertEquals(2.00, $data['price']['discount_amount']);
        $this->assertArrayHasKey('discount_type', $data['price']);
        $this->assertEquals(Discount::CONCRETE, $data['price']['discount_type']);

    }

    public function testConfigSubscribingMethods()
    {
        $config = ProductHandler::getSubscribingMethods();
        $this->assertEquals(1, count($config));
        $config = $config[0];
        $this->assertArrayHasKey('direction', $config);
        $this->assertEquals(GraphNavigator::DIRECTION_SERIALIZATION, $config['direction']);
        $this->assertArrayHasKey('format', $config);
        $this->assertEquals('json', $config['format']);
        $this->assertArrayHasKey('type', $config);
        $this->assertEquals('App\\Entity\\Product', $config['type']);
        $this->assertArrayHasKey('method', $config);
        $this->assertEquals('serializeProductToJson', $config['method']);
    }
}

