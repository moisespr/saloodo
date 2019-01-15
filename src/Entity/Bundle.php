<?php
namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity
 */
class Bundle extends Product
{

    /**
     * @ORM\ManyToMany(targetEntity="Product")
     * @ORM\JoinTable(name="bundles_products", 
     *  joinColumns={@ORM\JoinColumn(name="bundle_id", referencedColumnName="id")},
     *  inverseJoinColumns={@ORM\JoinColumn(name="product_id", referencedColumnName="id")}
     * )
     * @Assert\Count(
     *  min = 1
     * )
     */
    private $products;

    public function __construct(string $name = null, Price $price = null)
    {
        parent::__construct($name, $price);

        $this->products = new ArrayCollection();
    }

    public function addProduct($product)
    {
        $this->products->add($product);
    }

    public function addProducts(array $products)
    {
        foreach($products as $product)
        {
            $this->addProduct($product);
        }
    }

    public function getProducts()
    {
        return $this->products;
    }

    public function removeProduct($product)
    {
        $this->products->removeElement($product);
    }

    public function removeProducts(array $products)
    {
        foreach($products as $product)
        {
            $this->removeProduct($product);
        }
    }

    public function getType() {
        return 'Bundle';
    }

} 
