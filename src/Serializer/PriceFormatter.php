<?php
namespace App\Serializer;

use App\Entity\Price;

interface PriceFormatter 
{
    public function getAmount(Price $price);
    public function getFinalPrice(Price $price);
    public function getDiscount(Price $price);
    public function getDiscountType(Price $price);
}
