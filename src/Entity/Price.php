<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

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
     * @ORM\Column(type="integer", name="final_price")
    */
    private $finalPrice;    

    /**
     * @ORM\Column(type="integer", nullable=true, name="discount_amount")
     *
     * Original idea was to embbed a Discount here, but Doctrine doesn't support nullable embbedables
     */
    private $discountAmount;

    /**
     * @ORM\Column(type="string", nullable=true, name="discount_type")
     */
    private $discountType;

    public function __construct(int $amount = NULL, ?Discount $discount = NULL)
    {
        $this->setAmount($amount);
        $this->setDiscount($discount);
    }

    public function setAmount(?int $amount)
    {
        if(is_null($amount)) {
            return;
        }
        $this->amount = $amount;
        $this->finalPrice = $this->calculateFinalPrice();
    }

    public function setDiscount(?Discount $discount)
    {
        if(is_null($discount)) {
            $this->setDiscountToNull();
        } else {
            $this->setDiscountFromInstance($discount);
        }
        $this->finalPrice = $this->calculateFinalPrice();
    }

    private function setDiscountToNull()
    {
        $this->discountAmount = null;
        $this->discountType = null;
    }

    private function setDiscountFromInstance(Discount $discount)
    {
         $this->discountAmount = $discount->getAmount();
         $this->discountType = $discount->getType();
    }

    private function calculateFinalPrice()
    {
        $discount = $this->calculateDiscountAmount();
        return $this->amount - $discount;
    }

    private function calculateDiscountAmount()
    {
        if(!$this->hasDiscount()) {
            return 0;
        }
        switch($this->discountType)
        {
            case Discount::PERCENTUAL:
                return $this->calculatePercentualDiscountAmount($this->discountAmount, $this->amount);
            case Discount::CONCRETE:
                return $this->discountAmount;
            default:
                return 0;
        }
    }

    private function calculatePercentualDiscountAmount($discountAmount, $priceAmount)
    {
        $decimal = $discountAmount / 100.0;
        $percent = $decimal / 100.0;
        return $priceAmount * $percent;
    }

    public function getAmount() 
    {
        return $this->amount;
    }

    public function getDiscount()
    {
        if(!$this->hasDiscount()) {
            return null;
        }
        return new Discount($this->discountAmount, $this->discountType);
    }

    public function hasDiscount()
    {
        return !is_null($this->discountAmount) && $this->discountAmount > 0;
    }

    public function getFinalPrice()
    {
        return $this->finalPrice;
    }

}

