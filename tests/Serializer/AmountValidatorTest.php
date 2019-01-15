<?php
namespace App\Tests\Serializer;

use App\Serializer\AmountValidator;

use PHPUnit\Framework\TestCase;

class AmountValidatorTest extends TestCase 
{
    public function testIntegerNoCurrency()
    {
        $amount = '10';
        $validator = new AmountValidator($amount);
        $this->assertTrue($validator->isValid());
    }

    public function testDecimalAmountNoCurrency()
    {
        $amount = '5.50';
        $validator = new AmountValidator($amount);
        $this->assertTrue($validator->isValid());
    }

    public function testOneDecimalAmountNoCurrency()
    {
        $amount = '5.5';
        $validator = new AmountValidator($amount);
        $this->assertTrue($validator->isValid());
    }

    public function testIntegerAmountWithCurrency()
    {
        $amount = '10EUR';
        $validator = new AmountValidator($amount);
        $this->assertTrue($validator->isValid());
    }

    public function testDecimalAmountWithCurrency()
    {
        $amount = '5.50EUR';
        $validator = new AmountValidator($amount);
        $this->assertTrue($validator->isValid());
    } 

    public function testOneDecimalAmountWithCurrency()
    {
        $amount = '5.5EUR';
        $validator = new AmountValidator($amount);
        $this->assertTrue($validator->isValid());
    }

    public function testIntegerNoCurrencyNegative()
    {
        $amount = '-10';
        $validator = new AmountValidator($amount);
        $this->assertTrue($validator->isValid());
    }

    public function testDecimalAmountNoCurrencyNegative()
    {
        $amount = '-5.50';
        $validator = new AmountValidator($amount);
        $this->assertTrue($validator->isValid());
    }

    public function testOneDecimalAmountNoCurrencyNegative()
    {
        $amount = '-5.5';
        $validator = new AmountValidator($amount);
        $this->assertTrue($validator->isValid());
    }

    public function testIntegerAmountWithCurrencyNegative()
    {
        $amount = '-10EUR';
        $validator = new AmountValidator($amount);
        $this->assertTrue($validator->isValid());
    }

    public function testDecimalAmountWithCurrencyNegative()
    {
        $amount = '-5.50EUR';
        $validator = new AmountValidator($amount);
        $this->assertTrue($validator->isValid());
    } 

    public function testOneDecimalAmountWithCurrencyNegative()
    {
        $amount = '-5.5EUR';
        $validator = new AmountValidator($amount);
        $this->assertTrue($validator->isValid());
    }

    public function testLeadingZeros()
    {
        $amount = '010';
        $validator = new AmountValidator($amount);
        $this->assertFalse($validator->isValid());
    }

    public function testTwoNegativeSigns()
    {
        $amount = '--10';
        $validator = new AmountValidator($amount);
        $this->assertFalse($validator->isValid());

        $amount = '--5.50';
        $validator = new AmountValidator($amount);
        $this->assertFalse($validator->isValid());

        $amount = '--10EUR';
        $validator = new AmountValidator($amount);
        $this->assertFalse($validator->isValid());
    }

    public function testPositiveSign()
    {
        $amount = '+10';
        $validator = new AmountValidator($amount);
        $this->assertFalse($validator->isValid());

        $amount = '+5.50';
        $validator = new AmountValidator($amount);
        $this->assertFalse($validator->isValid());

        $amount = '+10EUR';
        $validator = new AmountValidator($amount);
        $this->assertFalse($validator->isValid());
    }

    public function testWrongPlaceSign()
    {
        $amount = '1-0';
        $validator = new AmountValidator($amount);
        $this->assertFalse($validator->isValid());

        $amount = '5.5-0';
        $validator = new AmountValidator($amount);
        $this->assertFalse($validator->isValid());

        $amount = '10-EUR';
        $validator = new AmountValidator($amount);
        $this->assertFalse($validator->isValid());
    }

    public function testInvalidComma()
    {
        $amount = '5,50';
        $validator = new AmountValidator($amount);
        $this->assertFalse($validator->isValid());

        $amount = '1,000.50';
        $validator = new AmountValidator($amount);
        $this->assertFalse($validator->isValid());

        $amount = '1,0EUR';
        $validator = new AmountValidator($amount);
        $this->assertFalse($validator->isValid());
    }

    public function testInvalidDigits()
    {
        $amount = '10EU';
        $validator = new AmountValidator($amount);
        $this->assertFalse($validator->isValid());

        $amount = '10EURO';
        $validator = new AmountValidator($amount);
        $this->assertFalse($validator->isValid());

        $amount = 'dfr';
        $validator = new AmountValidator($amount);
        $this->assertFalse($validator->isValid());

        $amount = 'tenEUR';
        $validator = new AmountValidator($amount);
        $this->assertFalse($validator->isValid());
    }

    public function testMoreThanOneDot()
    {
        $amount = '1.000.00';
        $validator = new AmountValidator($amount);
        $this->assertFalse($validator->isValid());
    }

    public function testCurrencyAtBeggining()
    {
        $amount = 'EUR10';
        $validator = new AmountValidator($amount);
        $this->assertFalse($validator->isValid());
    }

    public function testEmpty()
    {
        $amount = '';
        $validator = new AmountValidator($amount);
        $this->assertFalse($validator->isValid());
    }
}

