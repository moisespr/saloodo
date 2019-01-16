<?php
namespace App\Tests\Controller;

//use App\Controller\CustomerController;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

class CustomerControllerTest extends WebTestCase
{
    use RefreshDatabaseTrait;

    private function createAdminClient() {
        return static::createClient([], [
            'HTTP_X-AUTH-TOKEN' => 'ADMIN_TOKEN'
        ]);
    }

    public function testGetCustomers()
    {
        $client = $this->createAdminClient();
    
        $client->request('GET', '/customers');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('customers', $data);
        $this->assertEquals(1, count($data['customers']));
    }

    public function testGetCustomer()
    { 
        $client = $this->createAdminClient();

        $client->request('GET', '/customers/1');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('name', $data);
        $this->assertEquals('Customer 1', $data['name']);
    }

    public function testGetCustomerInexistent()
    { 
        $client = $this->createAdminClient();

        $client->request('GET', '/customers/11');

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testDeleteCustomerInexistent()
    {
        $client = $this->createAdminClient();

        $client->request('DELETE', '/customers/11');

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
     }

    public function testUpdateCustomerInexistent()
    {
        $client = $this->createAdminClient();

        $request_data = [
            'name' => 'Customer New Name'
        ];

        $client->request(
            'PATCH',
            '/customers/11',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            json_encode($request_data)
        );

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }
 
    public function testCreateCustomer()
    {
        $client = $this->createAdminClient();
        
        $request_data = [
            'name' => 'Customer 2',
        ];

        $client->request(
            'POST',
            '/customers',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            json_encode($request_data)
        );

        $this->assertEquals(201, $client->getResponse()->getStatusCode());
        
        $data = json_decode($client->getResponse()->getContent(), true);        

        $this->assertArrayHasKey('name', $data);
        $this->assertEquals('Customer 2', $data['name']);
    }

    public function testCreateCustomerNoName()
    {
        $client = $this->createAdminClient();
        
        $request_data = [
        ];

        $client->request(
            'POST',
            '/customers',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            json_encode($request_data)
        );

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

}

