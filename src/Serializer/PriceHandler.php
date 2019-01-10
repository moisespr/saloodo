<?php
namespace App\Serializer;

use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\JsonSerializationVisitor;
use JMS\Serializer\JsonDeserializationVisitor;
use JMS\Serializer\Context;

use App\Entity\Price;

class PriceHandler implements SubscribingHandlerInterface
{
    public static function getSubscribingMethods()
    {
        return [
            [
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format' => 'json',
                'type' => 'Price',
                'method' => 'serializePriceToJson',
            ],
            [
                'direction' => GraphNavigator::DIRECTION_DESERIALIZATION,
                'format' => 'json',
                'type' => 'Price',
                'method' => 'deserializePriceFromJson',
            ],
        ];
    }

    public function serializePriceToJson(JsonSerializationVisitor $visitor, Price $price, array $type, Context $context)
    {
        $priceFormatter = new DefaultPriceFormatter();
        $json  = '{';
        $json .= '\'amount\': '.$priceFormatter->getAmount($price);
        $json .= ',\'final_price\': '.$priceFormatter->getFinalPrice($price);
        if($price->getDiscount()) {
            $json .= ',\'discount\': '.$priceFormatter->getDiscount($price);
            $json .= ',\'discount_type\': '.$priceFormatter->getDiscountType($price);
        }
        $json .= '}';
        return $json;
    }

    public function deserializePriceFromJson(JsonDeserializationVisitor $visitor, $priceAsString, array $type, Context $context)
    {
        $price = new Price();
        return $price;
    }
}

