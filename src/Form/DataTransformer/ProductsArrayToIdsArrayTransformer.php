<?php
namespace App\Form\DataTransformer;

use App\Entity\Product;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Form\DataTransformerInterface;

class ProductsArrayToIdsArrayTransformer implements DataTransformerInterface
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function transform($products)
    {
        if(!$products) {
            return [];
        }
        $productsIds = [];
        foreach($products as $product) {
            $productsIds[] = $product->getId();
        }
        return $productsIds;
    }

    public function reverseTransform($productsIds)
    {
        if(!$productsIds) {
            return [];
        }
        $products = [];
        foreach($productsIds as $productId) {
            $product = $this->entityManager
                ->getRepository(Product::class)
                ->find($productId);
            $products[] = $product;
        }

        return $products;
    }

}
