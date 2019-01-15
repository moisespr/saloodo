<?php
namespace App\Tests\Entity;

use App\Entity\Bundle;
use App\Entity\Product;
use App\Entity\Price;
use App\Entity\Discount;

use PHPUnit\Framework\TestCase;

class BundleTest extends TestCase
{
    public function testConstructor()
    {
        $discount = new Discount(100, Discount::CONCRETE);
        $amount = 1000;
        $price = new Price($amount, $discount);
        $name = 'Bundle 1';
        $bundle = new Bundle($name, $price);

        $this->assertEquals($name, $bundle->getName());
        $this->assertNotNull($bundle->getPrice());
    }

    public function testAddingProduct()
    {
        $discount = new Discount(100, Discount::CONCRETE);
        $price1 = new Price(1000, $discount);

        $productName1 = 'Product 1';
        $product1 = new Product($productName1, $price1);

        $bundlePrice = new Price(500);
        $name = 'Bundle 1';
        $bundle = new Bundle($name, $bundlePrice);

        $bundle->addProduct($product1);

        $products = $bundle->getProducts();
        $this->assertEquals($name, $bundle->getName());
        $this->assertEquals(1, $products->count());
        $this->assertNotNull($bundle->getPrice());
    }

    public function testAddingProducts()
    {
        $discount = new Discount(100, Discount::CONCRETE);
        $price1 = new Price(1000, $discount);

        $productName1 = 'Product 1';
        $product1 = new Product($productName1, $price1);

        $price2 = new Price(500);
        $productName2 = 'Product 2';
        $product2 = new Product($productName2, $price2);

        $bundlePrice = new Price(1000);
        $name = 'Bundle 1';
        $bundle = new Bundle($name, $bundlePrice);

        $bundle->addProducts([$product1, $product2]);

        $products = $bundle->getProducts();
        $this->assertEquals($name, $bundle->getName());
        $this->assertEquals(2, $products->count());
        $this->assertNotNull($bundle->getPrice());
    }

    public function testAddingProductsEmpty()
    {
        $bundlePrice = new Price(1000);
        $name = 'Bundle 1';
        $bundle = new Bundle($name, $bundlePrice);

        $bundle->addProducts([]);

        $products = $bundle->getProducts();
        $this->assertEquals($name, $bundle->getName());
        $this->assertEquals(0, $products->count());
        $this->assertNotNull($bundle->getPrice());
    }


    public function testRemovingProductsEmpty()
    {
        $discount = new Discount(100, Discount::CONCRETE);
        $price1 = new Price(1000, $discount);

        $productName1 = 'Product 1';
        $product1 = new Product($productName1, $price1);

        $price2 = new Price(500);
        $productName2 = 'Product 2';
        $product2 = new Product($productName2, $price2);

        $bundlePrice = new Price(1000);
        $name = 'Bundle 1';
        $bundle = new Bundle($name, $bundlePrice);

        $bundle->addProducts([$product1, $product2]);

        $bundle->removeProducts([]);

        $products = $bundle->getProducts();
        $this->assertEquals($name, $bundle->getName());
        $this->assertEquals(2, $products->count());
        $this->assertNotNull($bundle->getPrice());
    }


    public function testRemovingProduct()
    {
        $discount = new Discount(100, Discount::CONCRETE);
        $price1 = new Price(1000, $discount);

        $productName1 = 'Product 1';
        $product1 = new Product($productName1, $price1);

        $price2 = new Price(500);
        $productName2 = 'Product 2';
        $product2 = new Product($productName2, $price2);

        $bundlePrice = new Price(1000);
        $name = 'Bundle 1';
        $bundle = new Bundle($name, $bundlePrice);

        $bundle->addProducts([$product1, $product2]);

        $bundle->removeProduct($product1);

        $products = $bundle->getProducts();
        $this->assertEquals($name, $bundle->getName());
        $this->assertEquals(1, $products->count());
        $this->assertNotNull($bundle->getPrice());
    }

    public function testRemovingProducts()
    {
        $discount = new Discount(100, Discount::CONCRETE);
        $price1 = new Price(1000, $discount);

        $productName1 = 'Product 1';
        $product1 = new Product($productName1, $price1);

        $price2 = new Price(500);
        $productName2 = 'Product 2';
        $product2 = new Product($productName2, $price2);

        $price3 = new Price(1000);
        $productName3 = 'Product 3';
        $product3 = new Product($productName3, $price3);

        $bundlePrice = new Price(1500);
        $name = 'Bundle 1';
        $bundle = new Bundle($name, $bundlePrice);

        $bundle->addProducts([$product1, $product2, $product3]);

        $bundle->removeProducts([$product1, $product3]);

        $products = $bundle->getProducts();
        $this->assertEquals($name, $bundle->getName());
        $this->assertEquals(1, $products->count());
        $this->assertNotNull($bundle->getPrice());
    }
}
