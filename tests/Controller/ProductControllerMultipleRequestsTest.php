<?php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;

class ProductControllerMultipleRequestsTest extends WebTestCase
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

    public function testDeleteProductById() 
    {
        $client = $this->createAdminClient();

        $client->request('DELETE', '/products/4');
        $this->assertEquals(204, $client->getResponse()->getStatusCode());

        $client->request('GET', '/products/4');        
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testDeleteProductByIdHasBundle() 
    {
        $client = $this->createAdminClient();

        $client->request('DELETE', '/products/1');        
        $this->assertEquals(409, $client->getResponse()->getStatusCode());
    }

    public function testChangeConcretePriceInteger()
    {
        $client = $this->createAdminClient();

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

        $this->assertArrayHasKey('price', $data);
        $this->assertArrayHasKey('amount', $data['price']);
        $this->assertEquals('5.00', $data['price']['amount']);
    }

    public function testChangeConcretePriceIntegerWithCurrencyCode()
    {
        $client = $this->createAdminClient();

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
        $client = $this->createAdminClient();

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
        $client = $this->createAdminClient();
        
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
        $client = $this->createAdminClient();

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
        $this->assertArrayHasKey('discount_amount', $data['price']);
        $this->assertEquals('1.00', $data['price']['discount_amount']);
        $this->assertArrayHasKey('final_price', $data['price']);
        $this->assertEquals('9.00', $data['price']['final_price']);
        $this->assertArrayHasKey('discount_type', $data['price']);
        $this->assertEquals('CONCRETE', $data['price']['discount_type']);
    }

    public function testChangeConcreteDiscountIntegerWithCurrencyCode()
    {
        $client = $this->createAdminClient();

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
        $this->assertArrayHasKey('discount_amount', $data['price']);
        $this->assertEquals('1.00', $data['price']['discount_amount']);
        $this->assertArrayHasKey('final_price', $data['price']);
        $this->assertEquals('9.00', $data['price']['final_price']);
        $this->assertArrayHasKey('discount_type', $data['price']);
        $this->assertEquals('CONCRETE', $data['price']['discount_type']);
    }

    public function testChangeConcreteDiscountDecimal()
    {
        $client = $this->createAdminClient();

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
        $this->assertArrayHasKey('discount_amount', $data['price']);
        $this->assertEquals('1.50', $data['price']['discount_amount']);
        $this->assertArrayHasKey('final_price', $data['price']);
        $this->assertEquals('8.50', $data['price']['final_price']);
        $this->assertArrayHasKey('discount_type', $data['price']);
        $this->assertEquals('CONCRETE', $data['price']['discount_type']);
    }

    public function testChangeConcreteDiscountDecimalWithCurrencyCode()
    {
        $client = $this->createAdminClient();

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
        $this->assertArrayHasKey('discount_amount', $data['price']);
        $this->assertEquals('1.50', $data['price']['discount_amount']);
        $this->assertArrayHasKey('final_price', $data['price']);
        $this->assertEquals('8.50', $data['price']['final_price']);
        $this->assertArrayHasKey('discount_type', $data['price']);
        $this->assertEquals('CONCRETE', $data['price']['discount_type']);
    }
 
    public function testChangePercentualDiscount()
    {
        $client = $this->createAdminClient();

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
        $this->assertArrayHasKey('discount_amount', $data['price']);
        $this->assertEquals('10.00', $data['price']['discount_amount']);
        $this->assertArrayHasKey('final_price', $data['price']);
        $this->assertEquals('9.00', $data['price']['final_price']);
        $this->assertArrayHasKey('discount_type', $data['price']);
        $this->assertEquals('PERCENTUAL', $data['price']['discount_type']);
    }
 
    public function testChangeConcretePriceIntegerNegative()
    {
        $client = $this->createAdminClient();

        $request_data = ['amount' => '-5'];
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

    public function testChangeConcretePriceIntegerWithCurrencyCodeNegative()
    {
        $client = $this->createAdminClient();

        $request_data = ['amount' => '-5EUR'];
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

    public function testChangeConcretePriceDecimalNegative()
    {
        $client = $this->createAdminClient();

        $request_data = ['amount' => '-5.50'];
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

    public function testChangeConcretePriceDecimalWithCurrencyCodeNegative()
    {
        $client = $this->createAdminClient();
        
        $request_data = ['amount' => '-5.50EUR'];
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

    public function testChangeConcreteDiscountIntegerNegative()
    {
        $client = $this->createAdminClient();

        $request_data = ['discount' => '-1'];
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
        $this->assertArrayHasKey('discount_amount', $data['price']);
        $this->assertEquals('1.00', $data['price']['discount_amount']);
        $this->assertArrayHasKey('final_price', $data['price']);
        $this->assertEquals('9.00', $data['price']['final_price']);
        $this->assertArrayHasKey('discount_type', $data['price']);
        $this->assertEquals('CONCRETE', $data['price']['discount_type']);
    }

    public function testChangeConcreteDiscountIntegerWithCurrencyCodeNegative()
    {
        $client = $this->createAdminClient();

        $request_data = ['discount' => '-1EUR'];
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
        $this->assertArrayHasKey('discount_amount', $data['price']);
        $this->assertEquals('1.00', $data['price']['discount_amount']);
        $this->assertArrayHasKey('final_price', $data['price']);
        $this->assertEquals('9.00', $data['price']['final_price']);
        $this->assertArrayHasKey('discount_type', $data['price']);
        $this->assertEquals('CONCRETE', $data['price']['discount_type']);
    }

    public function testChangeConcreteDiscountDecimalNegative()
    {
        $client = $this->createAdminClient();

        $request_data = ['discount' => '-1.5'];
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
        $this->assertArrayHasKey('discount_amount', $data['price']);
        $this->assertEquals('1.50', $data['price']['discount_amount']);
        $this->assertArrayHasKey('final_price', $data['price']);
        $this->assertEquals('8.50', $data['price']['final_price']);
        $this->assertArrayHasKey('discount_type', $data['price']);
        $this->assertEquals('CONCRETE', $data['price']['discount_type']);
    }

    public function testChangeConcreteDiscountDecimalWithCurrencyCodeNegative()
    {
        $client = $this->createAdminClient();

        $request_data = ['discount' => '-1.5EUR'];
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
        $this->assertArrayHasKey('discount_amount', $data['price']);
        $this->assertEquals('1.50', $data['price']['discount_amount']);
        $this->assertArrayHasKey('final_price', $data['price']);
        $this->assertEquals('8.50', $data['price']['final_price']);
        $this->assertArrayHasKey('discount_type', $data['price']);
        $this->assertEquals('CONCRETE', $data['price']['discount_type']);
    }
 
    public function testChangePercentualDiscountNegative()
    {
        $client = $this->createAdminClient();

        $request_data = ['discount' => '-10%'];
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
        $this->assertArrayHasKey('discount_amount', $data['price']);
        $this->assertEquals('10.00', $data['price']['discount_amount']);
        $this->assertArrayHasKey('final_price', $data['price']);
        $this->assertEquals('9.00', $data['price']['final_price']);
        $this->assertArrayHasKey('discount_type', $data['price']);
        $this->assertEquals('PERCENTUAL', $data['price']['discount_type']);
    }
 
    public function testUpdateProductName()
    {
        $client = $this->createAdminClient();

        $name = 'Product New Name';
        $request_data = [
            'name' => $name
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

        $client->request('GET', '/products/1');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('name', $data);
        $this->assertEquals($name, $data['name']);
     }
 
    public function testUpdateProductPrice()
    {
        $client = $this->createAdminClient();

        $price = 15;
        $request_data = [
            'price' => $price
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
       
        $client->request('GET', '/products/1');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('price', $data);
        $this->assertArrayHasKey('amount', $data['price']);
        $this->assertEquals('15.00', $data['price']['amount']);
        $this->assertArrayHasKey('final_price', $data['price']);
        $this->assertEquals('15.00', $data['price']['final_price']);
    }

    public function testUpdateProductDiscount()
    {
        $client = $this->createAdminClient();

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
     
        $client->request('GET', '/products/1');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('price', $data);
        $this->assertArrayHasKey('discount_amount', $data['price']);
        $this->assertEquals('5.00', $data['price']['discount_amount']);
        $this->assertArrayHasKey('final_price', $data['price']);
        $this->assertEquals('9.50', $data['price']['final_price']);
        $this->assertArrayHasKey('discount_type', $data['price']);
        $this->assertEquals('PERCENTUAL', $data['price']['discount_type']);
    }
 
}
