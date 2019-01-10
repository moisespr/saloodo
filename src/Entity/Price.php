<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable
 */
class Price 
{
    /**
    * @ORM\Column(type="integer")
    */
    private $amount;

    /**
    * @ORM\Column(type="integer")
    */
    private $final_price;    

    /**
    * @ORM\Column(type="integer", nullable=TRUE)
    */
    private $discount;

    /**
    * @ORM\Column(type="string", length=255, nullable=TRUE)
    */
    private $discount_type;

    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    public function setFinalPrice($final_price)
    {
        $this->final_price = $final_price;
    }

    public function setDiscount($discount)
    {
        $this->discount = $discount;
    }

    public function setDiscountType($discount_type)
    {
        $this->discount_type = $discount_type;
    }

    public function getAmount() 
    {
        return $this->amount;
    }

    public function getDiscount()
    {
        return $this->discount;
    }

    public function getDiscountType()
    {
        return $this->discount_type;
    }

    public function getFinalPrice()
    {
        return $this->final_price;
    }

}

