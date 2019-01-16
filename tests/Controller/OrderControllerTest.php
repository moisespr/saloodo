<?php
namespace App\Tests\Controller;

use App\Entity\Discount;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

class OrderControllerTest extends WebTestCase
{
    use RefreshDatabaseTrait;

    private function createCustomerClient() {
        return static::createClient([], [
            'HTTP_X-AUTH-TOKEN' => 'CUSTOMER_TOKEN'
        ]);
    }

    private function createAdminClient() {
        return static::createClient([], [
            'HTTP_X-AUTH-TOKEN' => 'ADMIN_TOKEN'
        ]);
    }

    public function testGetOrderById()
    {
        $client = $this->createCustomerClient();
    
        $client->request('GET', '/orders/1');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

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
        $client = $this->createCustomerClient();

        $client->request('GET', '/orders/11');

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testCreateOrderOneProduct()
    {
        $client = $this->createCustomerClient();
        
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
        $client = $this->createCustomerClient();
        
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
        $client = $this->createCustomerClient();
        
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
        $client = $this->createCustomerClient();
        
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
        $client = $this->createCustomerClient();
        
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

