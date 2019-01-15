<?php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;

class BundleControllerMultipleRequestsTest extends WebTestCase
{
    use ReloadDatabaseTrait;

    public function testDeleteBundleById() 
    {
        $client = static::createClient();

        $client->request('DELETE', '/bundles/4');        
        $this->assertEquals(204, $client->getResponse()->getStatusCode());

        $client->request('GET', '/bundles/4');        
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testChangeConcretePriceInteger()
    {
        $client = static::createClient();

        $request_data = ['amount' => '5'];
        $client->request(
            'PATCH',
            '/bundles/4/price',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            json_encode($request_data)
        );

        $this->assertEquals(204, $client->getResponse()->getStatusCode());

        $client->request('GET', '/bundles/4');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('price', $data);
        $this->assertArrayHasKey('amount', $data['price']);
        $this->assertEquals('5.00', $data['price']['amount']);
    }

    public function testChangeConcretePriceIntegerWithCurrencyCode()
    {
        $client = static::createClient();

        $request_data = ['amount' => '5EUR'];
        $client->request(
            'PATCH',
            '/bundles/4/price',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            json_encode($request_data)
        );

        $this->assertEquals(204, $client->getResponse()->getStatusCode());

        $client->request('GET', '/bundles/4');

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
            '/bundles/4/price',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            json_encode($request_data)
        );

        $this->assertEquals(204, $client->getResponse()->getStatusCode());

        $client->request('GET', '/bundles/4');

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
            '/bundles/4/price', 
            array(), 
            array(), 
            array('CONTENT_TYPE' => 'application/json'),
            json_encode($request_data)
        );

        $this->assertEquals(204, $client->getResponse()->getStatusCode());

        $client->request('GET', '/bundles/4');

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
            '/bundles/4/price',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            json_encode($request_data)
        );

        $this->assertEquals(204, $client->getResponse()->getStatusCode());

        $client->request('GET', '/bundles/4');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('price', $data);
        $this->assertArrayHasKey('discount_amount', $data['price']);
        $this->assertEquals('1.00', $data['price']['discount_amount']);
        $this->assertArrayHasKey('final_price', $data['price']);
        $this->assertEquals('14.00', $data['price']['final_price']);
        $this->assertArrayHasKey('discount_type', $data['price']);
        $this->assertEquals('CONCRETE', $data['price']['discount_type']);
    }

    public function testChangeConcreteDiscountIntegerWithCurrencyCode()
    {
        $client = static::createClient();

        $request_data = ['discount' => '1EUR'];
        $client->request(
            'PATCH',
            '/bundles/4/price',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            json_encode($request_data)
        );

        $this->assertEquals(204, $client->getResponse()->getStatusCode());

        $client->request('GET', '/bundles/4');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('price', $data);
        $this->assertArrayHasKey('discount_amount', $data['price']);
        $this->assertEquals('1.00', $data['price']['discount_amount']);
        $this->assertArrayHasKey('final_price', $data['price']);
        $this->assertEquals('14.00', $data['price']['final_price']);
        $this->assertArrayHasKey('discount_type', $data['price']);
        $this->assertEquals('CONCRETE', $data['price']['discount_type']);
    }

    public function testChangeConcreteDiscountDecimal()
    {
        $client = static::createClient();

        $request_data = ['discount' => '1.5'];
        $client->request(
            'PATCH',
            '/bundles/4/price',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            json_encode($request_data)
        );

        $this->assertEquals(204, $client->getResponse()->getStatusCode());

        $client->request('GET', '/bundles/4');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('price', $data);
        $this->assertArrayHasKey('discount_amount', $data['price']);
        $this->assertEquals('1.50', $data['price']['discount_amount']);
        $this->assertArrayHasKey('final_price', $data['price']);
        $this->assertEquals('13.50', $data['price']['final_price']);
        $this->assertArrayHasKey('discount_type', $data['price']);
        $this->assertEquals('CONCRETE', $data['price']['discount_type']);
    }

    public function testChangeConcreteDiscountDecimalWithCurrencyCode()
    {
        $client = static::createClient();

        $request_data = ['discount' => '1.5EUR'];
        $client->request(
            'PATCH', 
            '/bundles/4/price', 
            array(), 
            array(), 
            array('CONTENT_TYPE' => 'application/json'),
            json_encode($request_data)
        );

        $this->assertEquals(204, $client->getResponse()->getStatusCode());

        $client->request('GET', '/bundles/4');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('price', $data);
        $this->assertArrayHasKey('discount_amount', $data['price']);
        $this->assertEquals('1.50', $data['price']['discount_amount']);
        $this->assertArrayHasKey('final_price', $data['price']);
        $this->assertEquals('13.50', $data['price']['final_price']);
        $this->assertArrayHasKey('discount_type', $data['price']);
        $this->assertEquals('CONCRETE', $data['price']['discount_type']);
    }
 
    public function testChangePercentualDiscount()
    {
        $client = static::createClient();

        $request_data = ['discount' => '10%'];
        $client->request(
            'PATCH',
            '/bundles/4/price',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            json_encode($request_data)
        );

        $this->assertEquals(204, $client->getResponse()->getStatusCode());

        $client->request('GET', '/products/4');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('price', $data);
        $this->assertArrayHasKey('discount_amount', $data['price']);
        $this->assertEquals('10.00', $data['price']['discount_amount']);
        $this->assertArrayHasKey('final_price', $data['price']);
        $this->assertEquals('13.50', $data['price']['final_price']);
        $this->assertArrayHasKey('discount_type', $data['price']);
        $this->assertEquals('PERCENTUAL', $data['price']['discount_type']);
    }
 
    public function testUpdateBundleName()
    {
        $client = static::createClient();

        $name = 'Bundle New Name';
        $request_data = [
            'name' => $name
        ];

        $client->request(
            'PATCH',
            '/bundles/4',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            json_encode($request_data)
        );

        $this->assertEquals(204, $client->getResponse()->getStatusCode());

        $client->request('GET', '/bundles/4');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('name', $data);
        $this->assertEquals($name, $data['name']);
     }
 
    public function testUpdateBundlePrice()
    {
        $client = static::createClient();

        $price = 15;
        $request_data = [
            'price' => $price
        ];

        $client->request(
            'PATCH',
            '/bundles/4',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            json_encode($request_data)
        );

        $this->assertEquals(204, $client->getResponse()->getStatusCode());
       
        $client->request('GET', '/bundles/4');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('price', $data);
        $this->assertArrayHasKey('amount', $data['price']);
        $this->assertEquals('15.00', $data['price']['amount']);
        $this->assertArrayHasKey('final_price', $data['price']);
        $this->assertEquals('15.00', $data['price']['final_price']);
    }

    public function testUpdateBundleDiscount()
    {
        $client = static::createClient();

        $request_data = [
            'discount' => '5%'
        ];

        $client->request(
            'PATCH',
            '/bundles/4',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            json_encode($request_data)
        );

        $this->assertEquals(204, $client->getResponse()->getStatusCode());
     
        $client->request('GET', '/bundles/4');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('price', $data);
        $this->assertArrayHasKey('discount_amount', $data['price']);
        $this->assertEquals('5.00', $data['price']['discount_amount']);
        $this->assertArrayHasKey('final_price', $data['price']);
        $this->assertEquals('14.25', $data['price']['final_price']);
        $this->assertArrayHasKey('discount_type', $data['price']);
        $this->assertEquals('PERCENTUAL', $data['price']['discount_type']);
    }
 
    public function testAddProductToBundle()
    {
        $client = static::createClient();

        $request_data = [
            'products' => [
                3
            ]
        ];

        $client->request(
            'POST',
            '/bundles/4/products',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            json_encode($request_data)
        );

        $this->assertEquals(204, $client->getResponse()->getStatusCode());
     
        $client->request('GET', '/bundles/4');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('products', $data);
        $this->assertEquals(3, count($data['products']));
    }
 
    public function testDeleteProductFromBundle()
    {
        $client = static::createClient();

        $request_data = [
            'products' => [
                1
            ]
        ];

        $client->request(
            'DELETE',
            '/bundles/4/products',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            json_encode($request_data)
        );

        $this->assertEquals(204, $client->getResponse()->getStatusCode());
     
        $client->request('GET', '/bundles/4');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('products', $data);
        $this->assertEquals(1, count($data['products']));
    }
  
}
