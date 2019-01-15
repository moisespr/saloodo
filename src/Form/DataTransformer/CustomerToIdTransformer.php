<?php
namespace App\Form\DataTransformer;

use App\Entity\Customer;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Form\DataTransformerInterface;

class CustomerToIdTransformer implements DataTransformerInterface
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function transform($customer)
    {
        if(is_null($customer)) {
            return null;
        }
        return $customer->getId();
    }

    public function reverseTransform($customerId)
    {
        if(is_null($customerId)) {
            return null;
        }
        $customer = $this->entityManager
                ->getRepository(Customer::class)
                ->find($customerId);
        return $customer;
    }

}
