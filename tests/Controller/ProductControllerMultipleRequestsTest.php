<?php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;

class ProductControllerMultipleRequestsTest extends WebTestCase
{
    use ReloadDatabaseTrait;

    public function testDeleteProductById() 
    {
        $client = static::createClient();

        $client->request('DELETE', '/products/1');        
        $this->assertEquals(204, $client->getResponse()->getStatusCode());

        $client->request('GET', '/products/1');        
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testChangeConcretePriceInteger()
    {
        $client = static::createClient();

        $request_data = ['amount' => '5'];
        $client->request(
            'PATCH',
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

        $this->assertArrayHasKiey('price', $data);
        $this->assertArrayHasKey('amount', $data['price']);
        $this->assertEquals('5.00', $data['price']['amount']);
    }

    public function testChangeConcretePriceIntegerWithCurrencyCode()
    {
        $client = static::createClient();

        $request_data = ['amount' => '5EUR'];
        $client->request(
            'PATCH',
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

        $this->assertArrayHasKey('price', $data);
        $this->assertArrayHasKey('amount', $data['price']);
        $this->assertEquals('5.00', $data['price']['amount']);
    }

    public function testChangeConcretePriceDecimal()
    {
        $client = static::createClient();

        $request_data = ['amount' => '5.50'];
        $client->request(
            'PATCH',
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

        $this->assertArrayHasKey('price', $data);
        $this->assertArrayHasKey('amount', $data['price']);
        $this->assertEquals('5.50', $data['price']['amount']);
    }

    public function testChangeConcretePriceDecimalWithCurrencyCode()
    {
        $client = static::createClient();
        
        $request_data = ['amount' => '5.50EUR'];
        $client->request(
            'PATCH', 
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
        
        $this->assertArrayHasKey('price', $data);
        $this->assertArrayHasKey('amount', $data['price']);
        $this->assertEquals('5.50', $data['price']['amount']);
    }

    public function testChangeConcreteDiscountInteger()
    {
        $client = static::createClient();

        $request_data = ['discount' => '1'];
        $client->request(
            'PATCH',
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

        $this->assertArrayHasKey('price', $data);
        $this->assertArrayHasKey('discount', $data['price']);
        $this->assertEquals('1.00', $data['price']['discount']);
        $this->assertArrayHasKey('final_price', $data['price']);
        $this->assertEquals('9.00', $data['price']['final_price']);
        $this->assertArrayHasKey('discount_type', $data['price']);
        $this->assertEquals('CONCRETE', $data['price']['discount_type']);
    }

    public function testChangeConcreteDiscountIntegerWithCurrencyCode()
    {
        $client = static::createClient();

        $request_data = ['discount' => '1EUR'];
        $client->request(
            'PATCH',
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

        $this->assertArrayHasKey('price', $data);
        $this->assertArrayHasKey('discount', $data['price']);
        $this->assertEquals('1.00', $data['price']['discount']);
        $this->assertArrayHasKey('final_price', $data['price']);
        $this->assertEquals('9.00', $data['price']['final_price']);
        $this->assertArrayHasKey('discount_type', $data['price']);
        $this->assertEquals('CONCRETE', $data['price']['discount_type']);
    }

    public function testChangeConcreteDiscountDecimal()
    {
        $client = static::createClient();

        $request_data = ['discount' => '1.5'];
        $client->request(
            'PATCH',
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

        $this->assertArrayHasKey('price', $data);
        $this->assertArrayHasKey('discount', $data['price']);
        $this->assertEquals('1.50', $data['price']['discount']);
        $this->assertArrayHasKey('final_price', $data['price']);
        $this->assertEquals('8.50', $data['price']['final_price']);
        $this->assertArrayHasKey('discount_type', $data['price']);
        $this->assertEquals('CONCRETE', $data['price']['discount_type']);
    }

    public function testChangeConcreteDiscountDecimalWithCurrencyCode()
    {
        $client = static::createClient();

        $request_data = ['discount' => '1.5EUR'];
        $client->request(
            'PATCH', 
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

        $this->assertArrayHasKey('price', $data);
        $this->assertArrayHasKey('discount', $data['price']);
        $this->assertEquals('1.50', $data['price']['discount']);
        $this->assertArrayHasKey('final_price', $data['price']);
        $this->assertEquals('8.50', $data['price']['final_price']);
        $this->assertArrayHasKey('discount_type', $data['price']);
        $this->assertEquals('CONCRETE', $data['price']['discount_type']);
    }

    public function testChangePercentualDiscount()
    {
        $client = static::createClient();

        $request_data = ['discount' => '10%'];
        $client->request(
            'PATCH',
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

        $this->assertArrayHasKey('price', $data);
        $this->assertArrayHasKey('discount', $data['price']);
        $this->assertEquals('10.00', $data['price']['discount']);
        $this->assertArrayHasKey('final_price', $data['price']);
        $this->assertEquals('9.00', $data['price']['final_price']);
        $this->assertArrayHasKey('discount_type', $data['price']);
        $this->assertEquals('PERCENTUAL', $data['price']['discount_type']);
    }
}
