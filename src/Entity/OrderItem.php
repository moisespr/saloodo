<?php
namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

use App\Serializer\NumericAmountToDecimalString;

/**
 * @ORM\Entity
 * @ORM\Table(name="order_item")
 */
class OrderItem
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Order", inversedBy="itens")
     * @ORM\JoinColumn(name="order_id", referencedColumnName="id")
     */
    private $order;

    /**
     * @ORM\OneToOne(targetEntity="Product")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    private $product;

    /**
     * @ORM\Column(type="integer", name="price_at_purchase")
     * @Serializer\AccessType("public_method")
     * @Serializer\Type("string")
     */
    private $priceAtPurchase;

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setOrder($order)
    {
        $this->order = $order;
    }

    public function getOrder()
    {
        return $this->order;
    }

    public function setProduct($product)
    {
        $this->product = $product;
        $finalPrice = $product->getPrice()->getFinalPrice();
        $this->setPriceAtPurchase($finalPrice);
    }

    public function getProduct()
    {
        return $this->product;
    }

    public function setPriceAtPurchase($price)
    {
        $this->priceAtPurchase = $price;
    }

    public function getPriceAtPurchase()
    {
        return NumericAmountToDecimalString::convert($this->priceAtPurchase);
    }

}
