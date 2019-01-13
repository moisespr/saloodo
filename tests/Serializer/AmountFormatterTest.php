<?php
namespace App\Tests\Serializer;

use App\Serializer\AmountFormatter;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AmountFormatterTest extends WebTestCase
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
}
