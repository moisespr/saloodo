<?php
namespace App\Serializer;

use App\Entity\Currency;

class AmountFormatter
{

    private $amount;

    private $formatted;

    public function __construct($amount)
    {
        $this->amount = $amount;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
        $this->cleanFormatted();
    }

    private function cleanFormatted()
    {
        $this->formatted = null;
    }

    private function format()
    {
        if(!$this->isValid()) {
            throw Exception('Invalid formatted amount provided.');
        }

        $formatting = $this->amount;

        if($this->hasCurrency($formatting)) 
        {
            // lets just ignore currency for now as the design doesn't support it
            $formatting = $this->removeCurrency($formatting);
        }

        $this->formatted = $this->toIntegerDecimalRepresentation($formatting);
    }

    private function isValid()
    {
        return true;
    }

    private function hasCurrency($amount)
    {
        $currency = substr($amount, -3);
        return in_array($currency, Currency::ALL);
    }

    private function removeCurrency($amount)
    {
        return substr($amount, 0, strlen($amount)-3);
    }

    private function toIntegerDecimalRepresentation($amount)
    {
        return $amount * 100;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function getFormatted() : string
    {
        if(!$this->formatted) {
            $this->format();
        }
        return $this->formatted;
    }

}
