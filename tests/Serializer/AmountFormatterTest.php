<?php
namespace App\Tests\Serializer;

use App\Serializer\AmountFormatter;

use PHPUnit\Framework\TestCase;

class AmountFormatterTest extends TestCase
{

    public function testIntegerAmountNoCurrency() 
    {
        $amount = '10';
        $formatter = new AmountFormatter($amount);
        $this->assertEquals('1000', $formatter->getFormatted());
    }

    public function testDecimalAmountNoCurrency() 
    {
        $amount = '5.50';
        $formatter = new AmountFormatter($amount);
        $this->assertEquals('550', $formatter->getFormatted());
    }

    public function testOneDecimalAmountNoCurrency() 
    {
        $amount = '5.5';
        $formatter = new AmountFormatter($amount);
        $this->assertEquals('550', $formatter->getFormatted());
    }

    public function testIntegerAmountWithCurrency() 
    {
        $amount = '10EUR';
        $formatter = new AmountFormatter($amount);
        $this->assertEquals('1000', $formatter->getFormatted());
    }

    public function testDecimalAmountWithCurrency() 
    {
        $amount = '5.50EUR';
        $formatter = new AmountFormatter($amount);
        $this->assertEquals('550', $formatter->getFormatted());
    }

    public function testOneDecimalAmountWithCurrency() 
    {
        $amount = '5.5EUR';
        $formatter = new AmountFormatter($amount);
        $this->assertEquals('550', $formatter->getFormatted());
    }

    public function testSettingWithMethod()
    {
        $amount = '5.5EUR';
        $formatter = new AmountFormatter($amount);
        $formatter->setAmount('3.23EUR');
        $this->assertEquals('323', $formatter->getFormatted());
    }

    public function testGetAmount() 
    {
        $amount = '10';
        $formatter = new AmountFormatter($amount);
        $this->assertEquals('10', $formatter->getAmount());
    }

    public function testIntegerAmountNoCurrencyNegative() 
    {
        $amount = '-10';
        $formatter = new AmountFormatter($amount);
        $this->assertEquals('1000', $formatter->getFormatted());
    }

    public function testDecimalAmountNoCurrencyNegative() 
    {
        $amount = '-5.50';
        $formatter = new AmountFormatter($amount);
        $this->assertEquals('550', $formatter->getFormatted());
    }

    public function testOneDecimalAmountNoCurrencyNegative() 
    {
        $amount = '-5.5';
        $formatter = new AmountFormatter($amount);
        $this->assertEquals('550', $formatter->getFormatted());
    }

    public function testIntegerAmountWithCurrencyNegative() 
    {
        $amount = '-10EUR';
        $formatter = new AmountFormatter($amount);
        $this->assertEquals('1000', $formatter->getFormatted());
    }

    public function testDecimalAmountWithCurrencyNegative() 
    {
        $amount = '-5.50EUR';
        $formatter = new AmountFormatter($amount);
        $this->assertEquals('550', $formatter->getFormatted());
    }

    public function testOneDecimalAmountWithCurrencyNegative() 
    {
        $amount = '-5.5EUR';
        $formatter = new AmountFormatter($amount);
        $this->assertEquals('550', $formatter->getFormatted());
    }

}
