<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable
 */
class Discount 
{
    const CONCRETE = 'CONCRETE';
    const PERCENTUAL = 'PERCENTUAL';

    /**
    * @ORM\Column(type="integer")
    */
    private $amount;

    /**
    * @ORM\Column(type="string", length=255, nullable=TRUE)
    */
    private $discountType;

    public function __construct(int $amount = NULL, string $type = NULL)
    {
        $this->amount = abs($amount);
        $this->discountType = $type;
    }

    public function getAmount() : ?int
    {
        return $this->amount;
    }

    public function getType() : ?string
    {
        return $this->discountType;
    }

    public function setType(string $type) 
    {
        $this->discountType = $type;
    }

    public function setAmount(int $amount)
    {
        $this->amount = $amount;
    }

}

