<?php
namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $name;

    /**
     * @ORM\Embedded(class = "Price")
     * @Assert\NotBlank
     */
    private $price;

    public function __construct(string $name = null, Price $price = null)
    {
        $this->name = $name;
        $this->price = $price;
    }
 
    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function setPrice(Price $price)
    {
        $this->price = $price;
    }

    public function setDiscount(Discount $discount)
    {
        if(is_null($discount) || is_null($this->price)) {
            return;
        }
        $this->price->setDiscount($discount);
    }

    public function getName()
    {
        return $this->name;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function getDiscount() {
        if(is_null($this->price)) {
            return null;
        }
        return $this->price->getDiscount();
    }

    public function getType() {
        return 'Product';
    }

}

