<?php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

class ProductControllerTest extends WebTestCase
{
    use RefreshDatabaseTrait;

    public function testGetProducts()
    {
        $client = static::createClient();
    
        $client->request('GET', '/products');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('products', $data);
        $this->assertEquals(3, count($data['products']));
    } 

/*
    public function testGetProductByIdNoDiscount()
    {
        $client = static::createClient();

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
        $this->assertArrayNotHasKey('discount', $data);
        $this->assertArrayNotHasKey('discount_type', $data);
    }

    public function testGetProductByIdWithConcreteDiscount()
    {
        $client = static::createClient();

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
        $this->assertArrayHasKey('discount', $data['price']);
        $this->assertEquals('1.00', $data['price']['discount']);
        $this->assertArrayHasKey('discount_type', $data['price']);
        $this->assertEquals('CONCRETE', $data['price']['discount_type']);        
    }

    public function testGetProductByIdWithPercentualDiscount()
    {
        $client = static::createClient();

        $client->request('GET', '/products/3');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('name', $data);
        $this->assertEquals('Product 3', $data['name']);
        $this->assertArrayHasKey('final_price', $data['price']);
        $this->assertEquals('8.00', $data['price']['final_price']);
        $this->assertArrayHasKey('amount', $data['price']);
        $this->assertEquals('10.00', $data['price']['amount']);
        $this->assertArrayHasKey('discount', $data['price']);
        $this->assertEquals('20.00', $data['price']['discount']);
        $this->assertArrayHasKey('discount_type', $data['price']);
        $this->assertEquals('PERCENTUAL', $data['price']['discount_type']);        
    }

    public function testGetProductByIdProductInexistent()
    {
        $client = static::createClient();

        $client->request('GET', '/products/11');

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testDeleteProductByIdProductInexistent()
    {
        $client = static::createClient();

        $client->request('DELETE', '/products/11');

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testChangeConcretePriceProductInexistent()
    {
        $client = static::createClient();

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
/*
    public function testChangeConcreteDiscountProductInexistent()
    {
        $client = static::createClient();

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
        $client = static::createClient();
        
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
        $this->assertArrayHasKey('final_price', $data);
        $this->assertEquals('34EUR', $data['final_price']);
        $this->assertArrayHasKey('original_price', $data);
        $this->assertEquals('40EUR', $data['original_price']);
        $this->assertArrayHasKey('discount', $data);
        $this->assertEquals('15%', $data['discount']);
    }

    public function testCreateProductNoDiscount()
    {
        $client = static::createClient();

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
        $this->assertArrayHasKey('final_price', $data);
        $this->assertEquals('40EUR', $data['final_price']);
        $this->assertArrayHasKey('original_price', $data);
        $this->assertEquals('40EUR', $data['original_price']);
        $this->assertArrayNotHasKey('discount', $data);
    }

    public function testCreateProductNoName()
    {
        $client = static::createClient();
        
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
        $client = static::createClient();
        
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

    public function testUpdateProductName()
    {
        $client = static::createClient();

        $request_data = [
            'name' => 'Product New Name'
        ];

        $client->request(
            'PATCH',
            '/products/1',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            json_encode($request_data)
        );

        $this->assertEquals(204, $client->getResponse()->getStatusCode());
    }

    public function testUpdateProductPrice()
    {
        $client = static::createClient();

        $request_data = [
            'price' => '15'
        ];

        $client->request(
            'PATCH',
            '/products/1',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            json_encode($request_data)
        );

        $this->assertEquals(204, $client->getResponse()->getStatusCode());
    }

    public function testUpdateProductDiscount()
    {
        $client = static::createClient();

        $request_data = [
            'discount' => '5%'
        ];

        $client->request(
            'PATCH',
            '/products/1',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            json_encode($request_data)
        );

        $this->assertEquals(204, $client->getResponse()->getStatusCode());
    }
 */
}
