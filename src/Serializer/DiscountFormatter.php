<?php
namespace App\Serializer;

use App\Entity\Discount;

class DiscountFormatter
{
    private $amountFormatter;
    public function __construct(AmountFormatter $amountFormatter)
    {
        $this->amountFormatter = $amountFormatter;
        $this->setUp();
    }

    private function setUp()
    {
        $this->type = $this->guessType();
        if($this->type === Discount::PERCENTUAL) {
            $amount = $this->formatAmount();
            $this->amountFormatter->setAmount($amount);
        }
    }

    private function formatAmount()
    {
        $amount = $this->amountFormatter->getAmount();
        $amount = $this->removePercentSign($amount);
        return $amount;
    }

    private function removePercentSign($amount)
    {
        return substr($amount, 0, strlen($amount) - 1);
    }

    private function guessType()
    {
        $amount = $this->amountFormatter->getAmount();
        if($this->IsPercentual($amount)) {
            return Discount::PERCENTUAL;
        }
        return Discount::CONCRETE;
    }

    private function isPercentual($amount)
    {
        return substr($amount, -1) === '%';
    }

    public function getAmountFormatted()
    {
        return $this->amountFormatter->getFormatted();
    }

    public function getDiscountType()
    {
        return $this->type;
    }

}
