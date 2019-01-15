<?php
namespace App\Tests\Controller;

use App\Entity\Discount;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

class OrderControllerTest extends WebTestCase
{
    use RefreshDatabaseTrait;

    public function testGetOrderById()
    {
        $client = static::createClient();
    
        $client->request('GET', '/orders/1');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        print_r($data);

        $this->assertArrayHasKey('items', $data);
        $this->assertEquals(2, count($data['items']));

        $this->assertArrayHasKey('total_price', $data);
        $this->assertEquals('23.00', $data['total_price']);

        $this->assertArrayHasKey('customer', $data);
        $this->assertArrayHasKey('id', $data['customer']);
        $this->assertEquals(1, $data['customer']['id']);
        $this->assertArrayHasKey('name', $data['customer']);
        $this->assertEquals('Customer 1', $data['customer']['name']);
    } 

    public function testGetOrderInexistent()
    {
        $client = static::createClient();

        $client->request('GET', '/orders/11');

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testCreateOrderOneProduct()
    {
        $client = static::createClient();
        
        $request_data = [
            'customer' => 1,
            'items' => [
                1
            ]
        ];

        $client->request(
            'POST',
            '/orders',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            json_encode($request_data)
        );

        $this->assertEquals(201, $client->getResponse()->getStatusCode());
        
        $data = json_decode($client->getResponse()->getContent(), true);        

        $this->assertArrayHasKey('items', $data);
        $this->assertEquals(1, count($data['items']));

        $this->assertArrayHasKey('total_price', $data);
        $this->assertEquals('10.00', $data['total_price']);

        $this->assertArrayHasKey('customer', $data);
        $this->assertArrayHasKey('id', $data['customer']);
        $this->assertEquals(1, $data['customer']['id']);
        $this->assertArrayHasKey('name', $data['customer']);
        $this->assertEquals('Customer 1', $data['customer']['name']);
    }

    public function testCreateOrderTwoProducts()
    {
        $client = static::createClient();
        
        $request_data = [
            'customer' => 1,
            'items' => [
                1, 2
            ]
        ];

        $client->request(
            'POST',
            '/orders',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            json_encode($request_data)
        );

        $this->assertEquals(201, $client->getResponse()->getStatusCode());
        
        $data = json_decode($client->getResponse()->getContent(), true);        

        $this->assertArrayHasKey('items', $data);
        $this->assertEquals(2, count($data['items']));

        $this->assertArrayHasKey('total_price', $data);
        $this->assertEquals('19.00', $data['total_price']);

        $this->assertArrayHasKey('customer', $data);
        $this->assertArrayHasKey('id', $data['customer']);
        $this->assertEquals(1, $data['customer']['id']);
        $this->assertArrayHasKey('name', $data['customer']);
        $this->assertEquals('Customer 1', $data['customer']['name']);
    }

    public function testCreateOrderNoProduct()
    {
        $client = static::createClient();
        
        $request_data = [
            'customer' => 1
        ];

        $client->request(
            'POST',
            '/orders',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            json_encode($request_data)
        );

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

    public function testCreateOrderProductsEmpty()
    {
        $client = static::createClient();
        
        $request_data = [
            'customer' => 1,
            'items' => []
        ];

        $client->request(
            'POST',
            '/orders',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            json_encode($request_data)
        );

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }


    public function testCreateOrderNoCustomer()
    {
        $client = static::createClient();
        
        $request_data = [
            'items' => [
                1
            ]
        ];

        $client->request(
            'POST',
            '/orders',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            json_encode($request_data)
        );

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

}

