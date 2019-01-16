<?php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;

class CustomerControllerMultipleRequestsTest extends WebTestCase
{
    use ReloadDatabaseTrait;

    private function createCustomerClient() {
        return $this->createAdminClient([], [
            'HTTP_X-AUTH-TOKEN' => 'CUSTOMER_TOKEN'
        ]);
    }

    private function createAdminClient() {
        return static::createClient([], [
            'HTTP_X-AUTH-TOKEN' => 'ADMIN_TOKEN'
        ]);
    }

    public function testUpdateCustomerName()
    {
        $client = $this->createAdminClient();

        $name = 'Customer New Name';
        $request_data = [
            'name' => $name
        ];

        $client->request(
            'PATCH',
            '/customers/1',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            json_encode($request_data)
        );

        $this->assertEquals(204, $client->getResponse()->getStatusCode());

        $client->request('GET', '/customers/1');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('name', $data);
        $this->assertEquals($name, $data['name']);
     }

}
