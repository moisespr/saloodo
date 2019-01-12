<?php
namespace App\Tests\Serializer;

use App\Serializer\DefaultPriceFormatter;
use App\Entity\Discount;
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
        $price = new Price(900);
        $amount = $this->formatter->getAmount($price);
        $this->assertEquals('9.00', $amount);
    }

    public function testGetAmountLesserThanOne()
    {
        $price = new Price(45);
        $amount = $this->formatter->getAmount($price);
        $this->assertEquals('0.45', $amount);
    }

    public function testGetAmountLesserThanOneTwoDecimals()
    {
        $price = new Price(5);
        $amount = $this->formatter->getAmount($price);
        $this->assertEquals('0.50', $amount);
    }

    public function testGetAmountLesserThanTenAndBiggerThanOne()
    {
        $price = new Price(1567);
        $amount = $this->formatter->getAmount($price);
        $this->assertEquals('15.67', $amount);
    }

    public function testGetAmountPriceNull()
    {
        $this->expectException(\TypeError::class);
        $this->formatter->getAmount(null);
    }

    public function testGetFinalPricePriceNull()
    {
        $this->expectException(\TypeError::class);
        $this->formatter->getFinalPrice(null);
    }

    public function testGetDiscountAmountPriceNull()
    {
        $this->expectException(\TypeError::class);
        $this->formatter->getDiscountAmount(null);
    }

    public function testGetDiscountTypePriceNull()
    {
        $this->expectException(\TypeError::class);
        $this->formatter->getDiscountType(null);
    }
    
    public function testGetFinalPriceNoDiscount()
    {
        $price = new Price(1567);
        $finalPrice = $this->formatter->getFinalPrice($price);
        $this->assertEquals('15.67', $finalPrice);
    }

    public function testGetDiscountAmountWhenPresent()
    {
        $discount = new Discount(2000, Discount::PERCENTUAL);
        $price = new Price(1000, $discount);
        $discountAmount = $this->formatter->getDiscountAmount($price);
        $this->assertEquals('20.00', $discountAmount);
    }

    public function testGetDiscountAmountWhenNotPresent()
    {
        $price = new Price(1000);
        $discountAmount = $this->formatter->getDiscountAmount($price);
        $this->assertEquals('', $discountAmount);
    }

    public function testGetDiscountTypeWhenPresent()
    {
        $discount = new Discount(2000, Discount::PERCENTUAL);
        $price = new Price(1000, $discount);
        $discountType = $this->formatter->getDiscountType($price);
        $this->assertEquals('PERCENTUAL', $discountType);
    }

    public function testGetDiscountTypeWhenNotPresent()
    {
        $price = new Price(1000);
        $discountType = $this->formatter->getDiscountType($price);
        $this->assertEquals('', $discountType);
    }
}
