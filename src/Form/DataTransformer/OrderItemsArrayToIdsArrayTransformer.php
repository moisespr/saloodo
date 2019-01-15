<?php
namespace App\Form\DataTransformer;

use App\Entity\Product;
use App\Entity\OrderItem;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Form\DataTransformerInterface;

class OrderItemsArrayToIdsArrayTransformer implements DataTransformerInterface
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function transform($items)
    {
        if(!$items) {
            return [];
        }
        $itemsIds = [];
        foreach($items as $item) {
            $itemsIds[] = $item->getId();
        }
        return $itemsIds;
    }

    public function reverseTransform($itemsIds)
    {
        if(!$itemsIds) {
            return [];
        }
        $items = [];
        foreach($itemsIds as $itemId) {
            $product = $this->entityManager
                ->getRepository(Product::class)
                ->find($itemId);
            $item = new OrderItem();
            $item->setProduct($product);
            $items[] = $item;
        }

        return $items;
    }

}
