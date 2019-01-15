<?php
namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

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
     * @Assert\LessThan(
     *  value = 100
     * )
     * @Assert\GreaterThan(
     *  value = 0
     * )
    */
    private $amount;

    /**
    * @ORM\Column(type="string", length=255)
    */
    private $discountType;

    public function __construct(int $amount, string $type)
    {
        $this->amount = abs($amount);
        $this->discountType = $type;
    }

    public function getAmount() : int
    {
        return $this->amount;
    }

    public function getType() : string
    {
        return $this->discountType;
    }
}

