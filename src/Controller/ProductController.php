<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\View;

use App\Entity\Product;

class ProductController extends AbstractFOSRestController
{
    public function getProductsAction()
    {
        $products = $this->getDoctrine()
            ->getRepository(Product::class)
            ->findAll();
   
        $data = ['products' => $products];
        
        return $this->handleView(
            $this->view($data), 
            Response::HTTP_OK
        );
    }

    public function getProductAction($id)
    {
        $product = $this->getDoctrine()
            ->getRepository(Product::class)
            ->find($id);

        if(!$product) {
            throw new ResourceNotFoundException('Product not found');
        }

        return $this->handleView(
            $this->view($product),
            Response::HTTP_OK
        );
    }

    public function deleteProductAction($id)
    {
        $product = $this->getDoctrine()
            ->getRepository(Product::class)
            ->find($id);

        if(!$product) {
            throw new ResourceNotFoundException('Product not found');
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($product);
        $entityManager->flush();
        
        $product = $this->getDoctrine()
                        ->getRepository(Product::class)
                                    ->find($id);
        return $this->handleView(
            $this->view()
        );
    }

    public function postProductsAction()
    {
    }

    public function patchProductAction($id)
    {
    }

    /**
    * @Patch("/products/{id}/price")
    */
    public function putProductPrice($id)
    {
        
    }
}
