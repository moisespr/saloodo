<?php
namespace App\Tests\Controller;

use App\Entity\Discount;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

class ProductControllerTest extends WebTestCase
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

    public function testGetProducts()
    {
        $client = $this->createCustomerClient();

        $client->request('GET', '/products');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('products', $data);
        $this->assertEquals(6, count($data['products']));
    } 

    public function testGetProductByIdNoDiscount()
    {
        $client = $this->createCustomerClient();

        $client->request('GET', '/products/1');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('name', $data);
        $this->assertEquals('Product 1', $data['name']);
        $this->assertArrayHasKey('price', $data);
        $this->assertArrayHasKey('amount', $data['price']);
        $this->assertEquals('10.00', $data['price']['amount']);
        $this->assertArrayHasKey('final_price', $data['price']);
        $this->assertEquals('10.00', $data['price']['final_price']);
        $this->assertArrayNotHasKey('discount_amount', $data);
        $this->assertArrayNotHasKey('discount_type', $data);
        $this->assertArrayHasKey('type', $data);
        $this->assertEquals('Product', $data['type']);
    }

    public function testGetProductByIdWithConcreteDiscount()
    {
        $client = $this->createCustomerClient();

        $client->request('GET', '/products/2');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('name', $data);
        $this->assertEquals('Product 2', $data['name']);
        $this->assertArrayHasKey('price', $data);
        $this->assertArrayHasKey('final_price', $data['price']);
        $this->assertEquals('9.00', $data['price']['final_price']);
        $this->assertArrayHasKey('amount', $data['price']);
        $this->assertEquals('10.00', $data['price']['amount']);
        $this->assertArrayHasKey('discount_amount', $data['price']);
        $this->assertEquals('1.00', $data['price']['discount_amount']);
        $this->assertArrayHasKey('discount_type', $data['price']);
        $this->assertEquals('CONCRETE', $data['price']['discount_type']);        
    }

    public function testGetProductByIdWithPercentualDiscount()
    {
        $client = $this->createCustomerClient();

        $client->request('GET', '/products/3');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('name', $data);
        $this->assertEquals('Product 3', $data['name']);
        $this->assertArrayHasKey('final_price', $data['price']);
        $this->assertEquals('8.00', $data['price']['final_price']);
        $this->assertArrayHasKey('amount', $data['price']);
        $this->assertEquals('10.00', $data['price']['amount']);
        $this->assertArrayHasKey('discount_amount', $data['price']);
        $this->assertEquals('20.00', $data['price']['discount_amount']);
        $this->assertArrayHasKey('discount_type', $data['price']);
        $this->assertEquals('PERCENTUAL', $data['price']['discount_type']);        
    }

    public function testGetProductByIdProductInexistent()
    {
        $client = $this->createCustomerClient();

        $client->request('GET', '/products/11');

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testDeleteProductByIdProductInexistent()
    {
        $client = $this->createAdminClient();

        $client->request('DELETE', '/products/11');

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testUpdateProductByIdProductInexistent()
    {
        $client = $this->createAdminClient();

        $request_data = [
            'name' => 'Product New Name'
        ];

        $client->request(
            'PATCH',
            '/products/11',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            json_encode($request_data)
        );

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testChangeConcretePriceProductInexistent()
    {
        $client = $this->createAdminClient();

        $request_data = ['amount' => '5.50EUR'];
        $client->request(
            'PATCH',
            '/products/11/price',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            json_encode($request_data)
        );

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testChangeConcreteDiscountProductInexistent()
    {
        $client = $this->createAdminClient();

        $request_data = ['value' => '5.50EUR'];
        $client->request(
            'PUT',
            '/products/11/discount',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            json_encode($request_data)
        );

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testCreateProduct()
    {
        $client = $this->createAdminClient();
        
        $request_data = [
            'name' => 'Product 4',
            'price' => '40EUR',
            'discount' => '15%'
        ];

        $client->request(
            'POST',
            '/products',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            json_encode($request_data)
        );

        $this->assertEquals(201, $client->getResponse()->getStatusCode());
        
        $data = json_decode($client->getResponse()->getContent(), true);        

        $this->assertArrayHasKey('name', $data);
        $this->assertEquals('Product 4', $data['name']);
        $this->assertArrayHasKey('price', $data); 
        $this->assertArrayHasKey('final_price', $data['price']);
        $this->assertEquals('34.00', $data['price']['final_price']);
        $this->assertArrayHasKey('amount', $data['price']);
        $this->assertEquals('40.00', $data['price']['amount']);
        $this->assertArrayHasKey('discount_amount', $data['price']);
        $this->assertEquals('15.00', $data['price']['discount_amount']);
        $this->assertArrayHasKey('discount_type', $data['price']);
        $this->assertEquals(Discount::PERCENTUAL, $data['price']['discount_type']);
    }

    public function testCreateProductNoDiscount()
    {
        $client = $this->createAdminClient();

        $request_data = [
            'name' => 'Product 4',
            'price' => '40EUR'
        ];

        $client->request(
            'POST',
            '/products',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            json_encode($request_data)
        );

        $this->assertEquals(201, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('name', $data);
        $this->assertEquals('Product 4', $data['name']);
        $this->assertArrayHasKey('price', $data); 
        $this->assertArrayHasKey('final_price', $data['price']);
        $this->assertEquals('40.00', $data['price']['final_price']);
        $this->assertArrayHasKey('amount', $data['price']);
        $this->assertEquals('40.00', $data['price']['amount']);
        $this->assertArrayNotHasKey('discount_amount', $data['price']);
        $this->assertArrayNotHasKey('discount_type', $data['price']);
    }

    public function testCreateProductNoName()
    {
        $client = $this->createAdminClient();
        
        $request_data = [
            'price' => '40EUR'
        ];

        $client->request(
            'POST',
            '/products',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            json_encode($request_data)
        );

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

    public function testCreateProductNoPrice()
    {
        $client = $this->createAdminClient();
        
        $request_data = [
            'name' => 'Product 4'
        ];

        $client->request(
            'POST',
            '/products',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            json_encode($request_data)
        );

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

}
