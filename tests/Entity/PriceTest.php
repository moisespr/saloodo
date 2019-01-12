<?php
namespace App\Tests\Entity;

use App\Entity\Price;
use App\Entity\Discount;

use PHPUnit\Framework\TestCase;

class PriceTest extends TestCase
{
    public function testConstructorNoDiscount()
    {
        $amount = 1000;
        
        $price = new Price($amount);
        
        $this->assertEquals($amount, $price->getAmount());
        $this->assertEquals($amount, $price->getFinalPrice());
        $this->assertNull($price->getDiscount());
    }

    public function testConstructorDiscountConcrete()
    {
        $amount = 1000;
        $discount = new Discount(100, Discount::CONCRETE);
        
        $price = new Price($amount, $discount);
        
        $this->assertEquals($amount, $price->getAmount());
        $this->assertEquals(900, $price->getFinalPrice());
    }

    public function testConstructorDiscountPercentual()
    {
        $amount = 1000;
        $discount = new Discount(2000, Discount::PERCENTUAL);
        
        $price = new Price($amount, $discount);
        
        $this->assertEquals($amount, $price->getAmount());
        $this->assertEquals(800, $price->getFinalPrice());
    }

    public function testSetterNoDiscount()
    {
        $amount = 1000;
        $discount = new Discount(150, Discount::CONCRETE);
        
        $price = new Price($amount, $discount);
        $price->setDiscount(null);

        $this->assertEquals($amount, $price->getAmount());
        $this->assertEquals($amount, $price->getFinalPrice());
        $this->assertNull($price->getDiscount());
    }

    public function testSetterDiscountConcrete()
    {
        $amount = 1000;
        $discount = new Discount(150, Discount::CONCRETE);
        
        $price = new Price($amount);
        $price->setDiscount($discount);
        
        $this->assertEquals($amount, $price->getAmount());
        $this->assertEquals(850, $price->getFinalPrice());
    }

    public function testSetterDiscountPercentual()
    {
        $amount = 1000;
        $discount = new Discount(3000, Discount::PERCENTUAL);

        $price = new Price($amount);
        $price->setDiscount($discount);

        $this->assertEquals($amount, $price->getAmount());
        $this->assertEquals(700, $price->getFinalPrice());
    }
}
