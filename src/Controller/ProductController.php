<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations\Put;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    public function getProductsAction()
    {
        $data = $this->buildDummyData();
        
        $response = new Response();
        $response->setStatusCode(Response::HTTP_OK);
        $response->setContent(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    private function buildDummyData() 
    {
        $data = [
            'products' => [
                ['name' => 'Product 1', 'final_price' => '10EUR', 'original_price' => '10EUR'],
                ['name' => 'Product 2', 'final_price' => '9EUR', 'original_price' => '10EUR', 'discount' => '1EUR'],
                ['name' => 'Product 3', 'final_price' => '8EUR', 'original_price' => '10EUR', 'discount' => '20%']
            ]
        ];
        return $data;
    }

    public function getProductAction($id)
    {
        if($id < 0 || $id > 3) {
            return new Response('', Response::HTTP_NOT_FOUND);
        }
        $data = $this->buildDummyData();

        $response = new Response();
        $response->setStatusCode(Response::HTTP_OK);
        $response->setContent(json_encode($data['products'][$id-1]));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    public function deleteProductAction($id)
    {
    }

    public function postProductsAction()
    {
    }

    public function patchProductAction($id)
    {
    }

    /**
    * @Put("/products/{id}/price")
    */
    public function putProductPrice($id)
    {
    }    

    /**
    * @Put("/products/{id}/discount")
    */
    public function putProductDiscount($id)
    {
    }
}
