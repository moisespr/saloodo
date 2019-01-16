<?php
namespace App\Tests\Controller;

use App\Entity\Discount;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

class BundleControllerTest extends WebTestCase
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

    public function testGetBundles()
    {
        $client = $this->createCustomerClient();
    
        $client->request('GET', '/bundles');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('bundles', $data);
        $this->assertEquals(2, count($data['bundles']));
    } 

    public function testGetBundleById()
    {
        $client = $this->createCustomerClient();
 
        $client->request('GET', '/bundles/5');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('name', $data);
        $this->assertEquals('Bundle 1', $data['name']);
        $this->assertArrayHasKey('price', $data);
        $this->assertArrayHasKey('amount', $data['price']);
        $this->assertEquals('15.00', $data['price']['amount']);
        $this->assertArrayHasKey('final_price', $data['price']);
        $this->assertEquals('15.00', $data['price']['final_price']);
        $this->assertArrayHasKey('products', $data);
        $this->assertEquals(2, count($data['products']));
        $this->assertArrayNotHasKey('discount_amount', $data);
        $this->assertArrayNotHasKey('discount_type', $data);
        $this->assertArrayHasKey('type', $data);
        $this->assertEquals('Bundle', $data['type']);
    }

    public function testGetBundleInexistent()
    {
        $client = $this->createCustomerClient();
 
        $client->request('GET', '/bundles/11');

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testGetBundleNotBundle()
    {
        $client = $this->createCustomerClient();
 
        $client->request('GET', '/bundles/1');

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testDeleteBundleInexistent()
    {
        $client = $this->createAdminClient();

        $client->request('DELETE', '/bundles/11');

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testUpdateBundleInexistent()
    {
        $client = $this->createAdminClient();

        $request_data = [
            'name' => 'Bundle New Name'
        ];

        $client->request(
            'PATCH',
            '/bundles/11',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            json_encode($request_data)
        );

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testChangeConcretePriceBundleInexistent()
    {
        $client = $this->createAdminClient();

        $request_data = ['amount' => '5.50EUR'];
        $client->request(
            'PATCH',
            '/bundles/11/price',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            json_encode($request_data)
        );

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testChangeConcreteDiscountBundleInexistent()
    {
        $client = $this->createAdminClient();

        $request_data = ['value' => '5.50EUR'];
        $client->request(
            'PATCH',
            '/bundles/11/discount',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            json_encode($request_data)
        );

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testCreateBundleOneProduct()
    {
        $client = $this->createAdminClient();
        
        $request_data = [
            'name' => 'Bundle 1',
            'price' => '10EUR',
            'products' => [
                1
            ]
        ];

        $client->request(
            'POST',
            '/bundles',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            json_encode($request_data)
        );

        $this->assertEquals(201, $client->getResponse()->getStatusCode());
        
        $data = json_decode($client->getResponse()->getContent(), true);        

        $this->assertArrayHasKey('name', $data);
        $this->assertEquals('Bundle 1', $data['name']);
        $this->assertArrayHasKey('price', $data); 
        $this->assertArrayHasKey('final_price', $data['price']);
        $this->assertEquals('10.00', $data['price']['final_price']);
        $this->assertArrayHasKey('amount', $data['price']);
        $this->assertEquals('10.00', $data['price']['amount']);
        $this->assertArrayNotHasKey('discount_amount', $data['price']);
        $this->assertArrayNotHasKey('discount_type', $data['price']);
        $this->assertArrayHasKey('products', $data);
        $this->assertEquals(1, count($data['products']));        
    }

    public function testCreateBundleTwoProducts()
    {
        $client = $this->createAdminClient();
        
        $request_data = [
            'name' => 'Bundle 1',
            'price' => '10EUR',
            'products' => [
                1, 2
            ]
        ];

        $client->request(
            'POST',
            '/bundles',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            json_encode($request_data)
        );

        $this->assertEquals(201, $client->getResponse()->getStatusCode());
        
        $data = json_decode($client->getResponse()->getContent(), true);        

        $this->assertArrayHasKey('name', $data);
        $this->assertEquals('Bundle 1', $data['name']);
        $this->assertArrayHasKey('price', $data); 
        $this->assertArrayHasKey('final_price', $data['price']);
        $this->assertEquals('10.00', $data['price']['final_price']);
        $this->assertArrayHasKey('amount', $data['price']);
        $this->assertEquals('10.00', $data['price']['amount']);
        $this->assertArrayNotHasKey('discount_amount', $data['price']);
        $this->assertArrayNotHasKey('discount_type', $data['price']);
        $this->assertArrayHasKey('products', $data);
        $this->assertEquals(2, count($data['products']));        
    }

    public function testCreateBundleNoProduct()
    {
        $client = $this->createAdminClient();
        
        $request_data = [
            'name' => 'Bundle 1',
            'price' => '10EUR'
        ];

        $client->request(
            'POST',
            '/bundles',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            json_encode($request_data)
        );

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

    public function testCreateBundleProductsEmpty()
    {
        $client = $this->createAdminClient();
        
        $request_data = [
            'name' => 'Bundle 1',
            'price' => '10EUR',
            'products' => []
        ];

        $client->request(
            'POST',
            '/bundles',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            json_encode($request_data)
        );

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }


    public function testCreateBundleNoName()
    {
        $client = $this->createAdminClient();
        
        $request_data = [
            'price' => '40EUR',
            'products' => [
                1
            ]
        ];

        $client->request(
            'POST',
            '/bundles',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            json_encode($request_data)
        );

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

    public function testCreateBundleNoPrice()
    {
        $client = $this->createAdminClient();
        
        $request_data = [
            'name' => 'Bundle 1',
            'products' => [
                1
            ]
        ];

        $client->request(
            'POST',
            '/bundles',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            json_encode($request_data)
        );

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

    public function testDeleteProductFromBundleProductsEmpty()
    {
        $client = $this->createAdminClient();

        $request_data = [
            'products' => [
            ]
        ];

        $client->request(
            'DELETE',
            '/bundles/5/products',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            json_encode($request_data)
        );

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

    public function testDeleteProductFromBundleNoProducts()
    {
        $client = $this->createAdminClient();

        $request_data = [
        ];

        $client->request(
            'DELETE',
            '/bundles/5/products',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            json_encode($request_data)
        );

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

    public function testAddProductToBundleProductsEmpty()
    {
        $client = $this->createAdminClient();

        $request_data = [
            'products' => [
            ]
        ];

        $client->request(
            'POST',
            '/bundles/5/products',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            json_encode($request_data)
        );

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

    public function testAddProductToBundleNoProducts()
    {
        $client = $this->createAdminClient();

        $request_data = [
        ];

        $client->request(
            'POST',
            '/bundles/5/products',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            json_encode($request_data)
        );

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }
}

