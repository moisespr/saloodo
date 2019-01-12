<?php
namespace App\Tests\Entity;

use App\Entity\Product;
use App\Entity\Price;
use App\Entity\Discount;

use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    public function testConstructor()
    {
        $discount = new Discount(100, Discount::CONCRETE);
        $amount = 1000;
        $price = new Price($amount, $discount);
        $name = "Product 1";
        $product = new Product($name, $price);

        $this->assertEquals($name, $product->getName());
        $this->assertNotNull($product->getPrice());
    }
}
