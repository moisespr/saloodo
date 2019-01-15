<?php
namespace App\Serializer;

use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\JsonSerializationVisitor;
use JMS\Serializer\JsonDeserializationVisitor;
use JMS\Serializer\Context;

use App\Entity\Discount;
use App\Entity\Product;
use App\Entity\Bundle;
use App\Entity\Price;

class ProductHandler implements SubscribingHandlerInterface
{
    public static function getSubscribingMethods()
    {
        return [
            [
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format' => 'json',
                'type' => 'App\\Entity\\Product',
                'method' => 'serializeProductToJson',
            ]        
        ];
    }

    public function serializeProductToJson(JsonSerializationVisitor $visitor, Product $product, array $type, Context $context)
    {
        $data = [
            'id' => $product->getId(),
            'name' => $product->getName(),
            'price' => $this->serializePriceToJson($product->getPrice()),
            'type' => $product->getType() 
        ];
        return $data;
    }

    private function serializePriceToJson(Price $price)
    {
        $priceFormatter = new DefaultPriceFormatter();
        $data = [
            'amount' => $priceFormatter->getAmount($price),
            'final_price' => $priceFormatter->getFinalPrice($price)
        ];
        if($price->hasDiscount()) {
            $data['discount_amount'] = $priceFormatter->getDiscountAmount($price);
            $data['discount_type'] = $priceFormatter->getDiscountType($price);
        }
        return $data;
    }    
    
}

