<?php
namespace App\Tests\Serializer;

use App\Serializer\DefaultPriceFormatter;
use App\Entity\Price;

use PHPUnit\Framework\TestCase;

class DefaultPriceFormatterTest extends TestCase
{
    private $formatter;

    protected function setUp()
    {
        $this->formatter = new DefaultPriceFormatter();
    }

    public function testGetAmountBiggerThanOneAndLesserThanTen()
    {
        $price = new Price();
        $price->setAmount(900);
        $amount = $this->formatter->getAmount($price);
        $this->assertEquals('9.00', $amount);
    }

    public function testGetAmountLesserThanOne()
    {
        $price = new Price();
        $price->setAmount(45);
        $amount = $this->formatter->getAmount($price);
        $this->assertEquals('0.45', $amount);
    }

    public function testGetAmountLesserThanTenAndBiggerThanOne()
    {
        $price = new Price();
        $price->setAmount(1567);
        $amount = $this->formatter->getAmount($price);
        $this->assertEquals('15.67', $amount);
    }

    public function testGetAmountPriceNull()
    {
        $this->expectException(\TypeError::class);
        $this->formatter->getAmount(null);
    }
    
    public function testGetFinalPrice()
    {
        $this->assertTrue(false);
    }

    public function testGetDiscountWhenPresent()
    {
        $this->assertTrue(false);
    }

    public function testGetDiscountWhenNotPresent()
    {
        $this->assertTrue(false);
    }

    public function testGetDiscountTypeWhenPresent()
    {
        $this->assertTrue(false);
    }

    public function testGetDiscountTypeWhenNotPresent()
    {
        $this->assertTrue(false);
    }
}
