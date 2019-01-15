<?php
namespace App\Serializer;

use App\Entity\Discount;
use App\Entity\Price;

class DefaultPriceFormatter implements PriceFormatter
{
    public function getAmount(Price $price) 
    {
        return NumericAmountToDecimalString::convert($price->getAmount());
    }

    public function getFinalPrice(Price $price)
    {
        return NumericAmountToDecimalString::convert($price->getFinalPrice());
    }

    public function getDiscountAmount(Price $price)
    {
        $discount = $price->getDiscount();
        if(is_null($discount)) {
            return '';
        }
        return NumericAmountToDecimalString::convert($discount->getAmount());
    }

    public function getDiscountType(Price $price)
    {
        $discount = $price->getDiscount();
        if(is_null($discount)) {
            return '';
        }
        return $discount->getType();
    } 

}

