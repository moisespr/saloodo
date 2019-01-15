<?php
namespace App\Tests\Serializer;

use App\Entity\Discount;
use App\Serializer\AmountFormatter;
use App\Serializer\DiscountFormatter;

use PHPUnit\Framework\TestCase;

class DiscountFormatterTest extends TestCase
{

    public function testConcreteInteger() 
    {
        $amount = '10';
        $amountFormatter = new AmountFormatter($amount);
        $formatter = new DiscountFormatter($amountFormatter);
        $this->assertEquals('1000', $formatter->getAmountFormatted());
        $this->assertEquals(Discount::CONCRETE, $formatter->getDiscountType());
    }

    public function testConcreteDecimal()
    {
        $amount = '5.50';
        $amountFormatter = new AmountFormatter($amount);
        $formatter = new DiscountFormatter($amountFormatter);
        $this->assertEquals('550', $formatter->getAmountFormatted());
        $this->assertEquals(Discount::CONCRETE, $formatter->getDiscountType());
    }

    public function testConcreteIntegerWithCurrency()
    {
        $amount = '10EUR';
        $amountFormatter = new AmountFormatter($amount);
        $formatter = new DiscountFormatter($amountFormatter);
        $this->assertEquals('1000', $formatter->getAmountFormatted());
        $this->assertEquals(Discount::CONCRETE, $formatter->getDiscountType());
    }

    public function testConcreteDecimalWithCurrency()
    {
        $amount = '5.50EUR';
        $amountFormatter = new AmountFormatter($amount);
        $formatter = new DiscountFormatter($amountFormatter);
        $this->assertEquals('550', $formatter->getAmountFormatted());
        $this->assertEquals(Discount::CONCRETE, $formatter->getDiscountType());
    }

    public function testPercentualInteger()
    {
        $amount = '10%';
        $amountFormatter = new AmountFormatter($amount);
        $formatter = new DiscountFormatter($amountFormatter);
        $this->assertEquals('1000', $formatter->getAmountFormatted());
        $this->assertEquals(Discount::PERCENTUAL, $formatter->getDiscountType());
    }
}
