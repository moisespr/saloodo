<?php
namespace App\Tests\Entity;

use App\Entity\Discount;

use PHPUnit\Framework\TestCase;

class DiscountTest extends TestCase
{
    public function testPositiveConcrete()
    {
        $discount = new Discount(100, Discount::CONCRETE);
        $this->assertEquals(100, $discount->getAmount());
        $this->assertEquals(Discount::CONCRETE, $discount->getType());       
    }

    public function testNegativeConcrete()
    {
        $discount = new Discount(-300, Discount::CONCRETE);
        $this->assertEquals(300, $discount->getAmount());
        $this->assertEquals(Discount::CONCRETE, $discount->getType());       
    }

    public function testPositivePercentual()
    {
        $discount = new Discount(1000, Discount::PERCENTUAL);
        $this->assertEquals(1000, $discount->getAmount());
        $this->assertEquals(Discount::PERCENTUAL, $discount->getType());       
    }

    public function testNegativePercentual()
    {
        $discount = new Discount(-2000, Discount::PERCENTUAL);
        $this->assertEquals(2000, $discount->getAmount());
        $this->assertEquals(Discount::PERCENTUAL, $discount->getType());       
    }
}
