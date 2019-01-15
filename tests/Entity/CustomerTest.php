<?php
namespace App\Tests\Entity;

use App\Entity\Customer;

use PHPUnit\Framework\TestCase;

class CustomerTest extends TestCase
{
    public function testConstructor()
    {
        $name = 'Customer 1';
        $customer = new Customer($name);

        $this->assertEquals($name, $customer->getName());
    }
}
