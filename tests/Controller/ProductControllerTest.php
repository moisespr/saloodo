<?php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductControllerTest extends WebTestCase
{
    public function testGetProducts()
    {
        $client = static::createClient();

        $client->request('GET', '/products');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('products', $data);
        $this->assertEquals(3, count($data['products']));
    } 

    public function testGetProductByIdNoDiscount()
    {
        $client = static::createClient();

        $client->request('GET', '/products/1');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('name', $data);
        $this->assertEquals('Product 1', $data['name']);
        $this->assertArrayHasKey('final_price', $data);
        $this->assertEquals('10EUR', $data['final_price']);
        $this->assertArrayHasKey('original_price', $data);
        $this->assertEquals('10EUR', $data['original_price']);
        $this->assertArrayNotHasKey('discount', $data);
    }

    public function testGetProductByIdWithConcreteDiscount()
    {
        $client = static::createClient();

        $client->request('GET', '/products/2');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('name', $data);
        $this->assertEquals('Product 2', $data['name']);
        $this->assertArrayHasKey('final_price', $data);
        $this->assertEquals('9EUR', $data['final_price']);
        $this->assertArrayHasKey('original_price', $data);
        $this->assertEquals('10EUR', $data['original_price']);
        $this->assertArrayHasKey('discount', $data);
        $this->assertEquals('1EUR', $data['discount']);
    }

    public function testGetProductByIdWithPercentualDiscount()
    {
        $client = static::createClient();

        $client->request('GET', '/products/3');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('name', $data);
        $this->assertEquals('Product 3', $data['name']);
        $this->assertArrayHasKey('final_price', $data);
        $this->assertEquals("8EUR", $data['final_price']);
        $this->assertArrayHasKey('original_price', $data);
        $this->assertEquals("10EUR", $data['original_price']);
        $this->assertArrayHasKey('discount', $data);
        $this->assertEquals('20%', $data['discount']);
    }

    public function testGetProductByIdProductInexistent()
    {
        $client = static::createClient();

        $client->request('GET', '/products/11');

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testDeleteProductById() 
    {
        $client = static::createClient();

        $client->request('DELETE', '/products/1');        
        $this->assertEquals(204, $client->getResponse()->getStatusCode());

        $client->request('GET', '/products/1');        
        $this->assertEquals(204, $client->getResponse()->getStatusCode());
    }

    public function testDeleteProductByIdProductInexistent()
    {
        $client = static::createClient();

        $client->request('DELETE', '/products/11');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testChangeConcretePriceInteger()
    {
        $client = static::createClient();

        $request_data = ['value' => '5'];
        $client->request(
            'PUT',
            '/products/1/price',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            json_encode($request_data)
        );

        $this->assertEquals(204, $client->getResponse()->getStatusCode());

        $client->request('GET', '/products/1');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('original_price', $data);
        $this->assertEquals('5EUR', $data['original_price']);
    }

    public function testChangeConcretePriceIntegerWithCurrencyCode()
    {
        $client = static::createClient();

        $request_data = ['value' => '5EUR'];
        $client->request(
            'PUT',
            '/products/1/price',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            json_encode($request_data)
        );

        $this->assertEquals(204, $client->getResponse()->getStatusCode());

        $client->request('GET', '/products/1');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('original_price', $data);
        $this->assertEquals('5EUR', $data['original_price']);
    }

    public function testChangeConcretePriceDecimal()
    {
        $client = static::createClient();

        $request_data = ['value' => '5.50'];
        $client->request(
            'PUT',
            '/products/1/price',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            json_encode($request_data)
        );

        $this->assertEquals(204, $client->getResponse()->getStatusCode());

        $client->request('GET', '/products/1');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('original_price', $data);
        $this->assertEquals('5.50EUR', $data['original_price']);
    }

    public function testChangeConcretePriceDecimalWithCurrencyCode()
    {
        $client = static::createClient();

        $request_data = ['value' => '5.50EUR'];
        $client->request(
            'PUT', 
            '/products/1/price', 
            array(), 
            array(), 
            array('CONTENT_TYPE' => 'application/json'),
            json_encode($request_data)
        );

        $this->assertEquals(204, $client->getResponse()->getStatusCode());

        $client->request('GET', '/products/1');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('original_price', $data);
        $this->assertEquals('5.50EUR', $data['original_price']);
    }

    public function testChangeConcretePriceProductInexistent()
    {
        $client = static::createClient();

        $request_data = ['value' => '5.50EUR'];
        $client->request(
            'PUT',
            '/products/11/price',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            json_encode($request_data)
        );

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testChangeConcreteDiscountInteger()
    {
        $client = static::createClient();

        $request_data = ['value' => '1'];
        $client->request(
            'PUT',
            '/products/1/discount',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            json_encode($request_data)
        );

        $this->assertEquals(204, $client->getResponse()->getStatusCode());

        $client->request('GET', '/products/1');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('discount', $data);
        $this->assertEquals('1EUR', $data['discount']);
    }

    public function testChangeConcreteDiscountIntegerWithCurrencyCode()
    {
        $client = static::createClient();

        $request_data = ['value' => '1EUR'];
        $client->request(
            'PUT',
            '/products/1/discount',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            json_encode($request_data)
        );

        $this->assertEquals(204, $client->getResponse()->getStatusCode());

        $client->request('GET', '/products/1');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('discount', $data);
        $this->assertEquals('1EUR', $data['discount']);
    }

    public function testChangeConcreteDiscountDecimal()
    {
        $client = static::createClient();

        $request_data = ['value' => '1.5'];
        $client->request(
            'PUT',
            '/products/1/discount',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            json_encode($request_data)
        );

        $this->assertEquals(204, $client->getResponse()->getStatusCode());

        $client->request('GET', '/products/1');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('discount', $data);
        $this->assertEquals('1.50EUR', $data['discount']);
    }

    public function testChangeConcreteDiscountDecimalWithCurrencyCode()
    {
        $client = static::createClient();

        $request_data = ['value' => '1.5EUR'];
        $client->request(
            'PUT', 
            '/products/1/discount', 
            array(), 
            array(), 
            array('CONTENT_TYPE' => 'application/json'),
            json_encode($request_data)
        );

        $this->assertEquals(204, $client->getResponse()->getStatusCode());

        $client->request('GET', '/products/1');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('discount', $data);
        $this->assertEquals('1.5EUR', $data['discount']);
    }

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

    public function testChangePercentualDiscount()
    {
        $client = static::createClient();

        $request_data = ['value' => '10%'];
        $client->request(
            'PUT',
            '/products/1/discount',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            json_encode($request_data)
        );

        $this->assertEquals(204, $client->getResponse()->getStatusCode());

        $client->request('GET', '/products/1');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('discount', $data);
        $this->assertEquals('10%', $data['discount']);
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
}
