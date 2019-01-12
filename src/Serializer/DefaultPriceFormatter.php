<?php
namespace App\Serializer;

use App\Entity\Discount;
use App\Entity\Price;

class DefaultPriceFormatter implements PriceFormatter
{
    public function getAmount(Price $price) 
    {
        return $this->toDecimalString($price->getAmount());
    }

    public function getFinalPrice(Price $price)
    {
        return $this->toDecimalString($price->getFinalPrice());
    }

    public function getDiscountAmount(Price $price)
    {
        $discount = $price->getDiscount();
        if(is_null($discount)) {
            return '';
        }
        return $this->toDecimalString($discount->getAmount());
    }

    public function getDiscountType(Price $price)
    {
        $discount = $price->getDiscount();
        if(is_null($discount)) {
            return '';
        }
        return $discount->getType();
    } 

    private function toDecimalString($value) : string
    {
        $formattedValue = '';
        if($value >= 100) {
            $formattedValue = $this->formatValueBiggerThanOne($value);
        } else {
            $formattedValue = $this->formatValueLesserThanOne($value);
        }
        return $formattedValue;
    }

    private function formatValueBiggerThanOne($value)
    {
        $firstPart = substr($value, 0, strlen($value)-2);
        $lastPart = substr($value, strlen($value)-2);
        return $firstPart.'.'.$lastPart;
    }

    private function formatValueLesserThanOne($value)
    {
        $lastPart = $value;
        if(strlen($lastPart) == 1) {
            $lastPart .= '0';
        }
        return '0.'.$lastPart;
    } 
}

