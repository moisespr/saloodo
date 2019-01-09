<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as FOSRest;

/**
* Products.
*
* @Route("/api")
*/
class ProductController extends AbstractController
{
    /**
    * Lists all Products.
    * @FOSRest\Get("/products")
    *
    * @return array
    */
    public function getProducts() : ?array
    {
        return null;
    }
}
