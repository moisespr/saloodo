<?php
namespace App\Serializer;

class AmountValidator
{

    private $amount;

    private $valid;

    public function __construct(string $amount)
    {
        $this->amount = $amount;
        $this->valid = $this->validate($amount);
    }

    private function validate($amount) 
    {
        return preg_match('/^-?[1-9][0-9]*\\.?[0-9]{0,2}([A-Z]{3})?$/', $amount) === 1;
    }

    public function isValid() {
        return $this->valid;
    }
}
