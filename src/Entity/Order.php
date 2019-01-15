<?php
namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

use App\Serializer\NumericAmountToDecimalString;

/**
 * @ORM\Entity
 * @ORM\Table(name="orders")
 */
class Order
{

    /** 
     * @ORM\Column(type="integer") @ORM\Version 
     * */
    private $version;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="OrderItem", mappedBy="order", cascade={"persist"})
     * @Assert\Count(
     *  min = 1
     * )
     */
    private $items;

    /**
     * @ORM\ManyToOne(targetEntity="Customer")
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id")
     * @Assert\NotBlank
     */
    private $customer;

    /**
     * @ORM\Column(type="integer")
     * @Serializer\AccessType("public_method")
     * @Serializer\Type("string")
     */
    private $totalPrice;

    public function __construct() {
        $this->items = new ArrayCollection();
    }
 
    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setCustomer($customer)
    {
        $this->customer = $customer;
    }

    public function getCustomer()
    {
        return $this->customer;
    }

    public function addItem($item) 
    {
        if(is_null($item)) {
            return;
        }
        $product = $item->getProduct();
        if(is_null($product)) {
            return;
        }
        $this->totalPrice += $product->getPrice()->getFinalPrice();
        $this->items->add($item);
    }

    public function removeItem($item)
    {
        $this->items->removeElement($item);
    }

    public function getItems() 
    {
        return $this->items;
    }

    public function getTotalPrice() 
    {
        return NumericAmountToDecimalString::convert($this->totalPrice);
    }

    public function setTotalPrice($totalPrice)
    {
    }

}
