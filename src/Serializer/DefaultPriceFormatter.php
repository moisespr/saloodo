<?php
namespace App\Serializer;

use App\Entity\Price;

class DefaultPriceFormatter implements PriceFormatter
{
    public function getAmount(Price $price) 
    {
        $amount = $price->getAmount();
        $formattedAmount = '';
        if($amount >= 100) {
            $formattedAmount = $this->formatBiggerThanOneAmount($amount);
        } else {
            $formattedAmount = $this->formatLesserThanOneAmount($amount);
        }
        return $formattedAmount;
    }

    private function formatBiggerThanOneAmount($amount)
    {
        $firstPart = substr($amount, 0, strlen($amount)-2);
        $lastPart = substr($amount, strlen($amount)-2);
        return $firstPart.'.'.$lastPart;
    }

    private function formatLesserThanOneAmount($amount)
    {
        return '0.'.$amount;
    } 

    public function getFinalPrice(Price $price)
    {
    }

    public function getDiscount(Price $price)
    {
    }

    public function getDiscountType(Price $price)
    {
    }
}

