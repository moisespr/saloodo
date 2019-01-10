<?php
namespace App\Tests\Serializer;

use App\Serializer\PriceHandler;
use App\Entity\Price;

use JMS\Serializer\JsonSerializationVisitor;
use JMS\Serializer\Context;

use PHPUnit\Framework\TestCase;

class PriceHandlerTest extends TestCase
{
    private $priceHandler;
    private $visitor;
    private $context;

    protected function setUp() 
    {
        $this->priceHandler = new PriceHandler();
        $this->visitor = new JsonSerializationVisitor();

        $this->context = new class extends Context {
             public function getDepth() : int { return 0; }
             public function getDirection() : int { return 0; }
         };
    }

    public function testSerializePriceToJSONNoDiscount()
    {
        $price = new Price();
        $price->setAmount(1000);

        $jsonPrice = $this->priceHandler->serializePriceToJson($this->visitor, $price, [], $this->context);

        $data = json_decode($jsonPrice, true);

        $this->assertArrayHasKey('amount', $data); 
        $this->assertArrayHasKey('final_price', $data);        
        $this->assertArrayNotHasKey('discount', $data);
        $this->assertArrayNotHasKey('discount_type', $data);
    }

    public function testDeserializePriceFromJSONPriceIntegerNoDiscount()
    {
        $this->assertEquals(false, true);
    }

    public function testDeserializePriceFromJSONPriceDecimalNoDiscount()
    {
        $this->assertEquals(false, true);
    }

    public function testDeserializePriceFromJSONPriceIntegerWithCurrencyNoDiscount()
    {
        $this->assertEquals(false, true);
    }

    public function testDeserializePriceFromJSONPriceDecimalWithCurrencyNoDiscount()
    {
        $this->assertEquals(false, true);
    }
    
    public function testDeserializePriceFromJSONPriceIntegerDiscountIntegerConcrete()
    {
        $this->assertEquals(false, true);
    }
    
    public function testDeserializePriceFromJSONPriceIntegerDiscountIntegerPercentual()
    {
        $this->assertEquals(false, true);
    }
    
    public function testDeserializePriceFromJSONPriceIntegerDiscountDecimalConcrete()
    {
        $this->assertEquals(false, true);
    }
    
    public function testDeserializePriceFromJSONPriceIntegerDiscountDecimalPercentual()
    {
        $this->assertEquals(false, true);
    }
    
}

