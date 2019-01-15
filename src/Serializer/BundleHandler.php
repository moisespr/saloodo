<?php
namespace App\Serializer;

use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\JsonSerializationVisitor;
use JMS\Serializer\JsonDeserializationVisitor;
use JMS\Serializer\Context;

use App\Entity\Bundle;

class BundleHandler implements SubscribingHandlerInterface
{
    public static function getSubscribingMethods()
    {
        return [
            [
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format' => 'json',
                'type' => 'App\\Entity\\Bundle',
                'method' => 'serializeBundleToJson',
            ]
        ];
    }

    private $productHandler;
    public function __construct()
    {
        $this->productHandler = new ProductHandler();
    }

    public function serializeBundleToJson(JsonSerializationVisitor $visitor, Bundle $bundle, array $type, Context $context)
    {
        $data = $this->productHandler->serializeProductToJson($visitor, $bundle, $type, $context);

        $productsData = [];
        
        $products = $bundle->getProducts();
        foreach($products as $product)
        {
            $productData = $this->productHandler->serializeProductToJson($visitor, $product, $type, $context);
            $productsData[] = $productData;
        }

        $data['products'] = $productsData;
        return $data;
    }
    
}

