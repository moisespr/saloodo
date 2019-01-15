<?php
namespace App\Tests\Serializer;

use App\Serializer\BundleHandler;
use App\Entity\Discount;
use App\Entity\Price;
use App\Entity\Bundle;
use App\Entity\Product;

use JMS\Serializer\GraphNavigator;
use JMS\Serializer\JsonSerializationVisitor;
use JMS\Serializer\JsonDeserializationVisitor;
use JMS\Serializer\Context;

use PHPUnit\Framework\TestCase;

class BundleHandlerTest extends TestCase
{
    private $bundleHandler;
    private $serializationVisitor;
    private $context;

    protected function setUp() 
    {
        $this->bundleHandler = new BundleHandler();
        $this->serializationVisitor = new JsonSerializationVisitor();

        $this->context = new class extends Context {
             public function getDepth() : int { return 0; }
             public function getDirection() : int { return 0; }
        };
    }

    public function testSerializeBundleToJSONOneProduct()
    {
        $productPrice = new Price(1000);
        $product = new Product('Product 1', $productPrice);

        $bundlePrice = new Price(1500);

        $bundle = new Bundle('Bundle 1', $bundlePrice);
        $bundle->addProduct($product);

        $data = $this->bundleHandler->serializeBundleToJson($this->serializationVisitor, $bundle, [], $this->context);

        $this->assertArrayHasKey('name', $data); 
        $this->assertEquals('Bundle 1', $data['name']);
        $this->assertArrayHasKey('price', $data);
        $this->assertArrayHasKey('amount', $data['price']);
        $this->assertEquals(15.00, $data['price']['amount']);
        $this->assertArrayHasKey('final_price', $data['price']);
        $this->assertEquals(15.00, $data['price']['final_price']);
        $this->assertArrayHasKey('products', $data);
        $this->assertEquals(1, count($data['products']));
        $this->assertArrayNotHasKey('discount_amount', $data['price']);
        $this->assertArrayNotHasKey('discount_type', $data['price']);
    }

    public function testSerializeBundleToJSONTwoProducts()
    {
        $productPrice = new Price(1000);
        $product1 = new Product('Product 1', $productPrice);

        $productPrice = new Price(450);
        $product2 = new Product('Product 2', $productPrice);

        $bundlePrice = new Price(1500);

        $bundle = new Bundle('Bundle 1', $bundlePrice);
        $bundle->addProducts([$product1, $product2]);

        $data = $this->bundleHandler->serializeBundleToJson($this->serializationVisitor, $bundle, [], $this->context);

        $this->assertArrayHasKey('name', $data); 
        $this->assertEquals('Bundle 1', $data['name']);
        $this->assertArrayHasKey('price', $data);
        $this->assertArrayHasKey('amount', $data['price']);
        $this->assertEquals(15.00, $data['price']['amount']);
        $this->assertArrayHasKey('final_price', $data['price']);
        $this->assertEquals(15.00, $data['price']['final_price']);
        $this->assertArrayHasKey('products', $data);
        $this->assertEquals(2, count($data['products']));
        $this->assertArrayNotHasKey('discount_amount', $data['price']);
        $this->assertArrayNotHasKey('discount_type', $data['price']);
    }

    public function testConfigSubscribingMethods()
    {
        $config = BundleHandler::getSubscribingMethods();
        $this->assertEquals(1, count($config));
        $config = $config[0];
        $this->assertArrayHasKey('direction', $config);
        $this->assertEquals(GraphNavigator::DIRECTION_SERIALIZATION, $config['direction']);
        $this->assertArrayHasKey('format', $config);
        $this->assertEquals('json', $config['format']);
        $this->assertArrayHasKey('type', $config);
        $this->assertEquals('App\\Entity\\Bundle', $config['type']);
        $this->assertArrayHasKey('method', $config);
        $this->assertEquals('serializeBundleToJson', $config['method']);
    }
}

