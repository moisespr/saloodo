<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\Patch;

use Doctrine\DBAL\Exception as DoctrineException;

use App\Form\PriceType;
use App\Form\ProductType;

use App\Entity\Product;

class ProductController extends AbstractFOSRestController
{
    /**
     * @IsGranted("ROLE_CUSTOMER")
     */
    public function getProductsAction()
    {
        $products = $this->getDoctrine()
            ->getRepository(Product::class)
            ->findAll();

        $data = ['products' => $products];
        
        return $this->handleView(
            $this->view($data, Response::HTTP_OK)
        );
    }

    /**
     * @IsGranted("ROLE_CUSTOMER")
     */
    public function getProductAction($id)
    {
        $product = $this->getDoctrine()
            ->getRepository(Product::class)
            ->find($id);

        if(!$product) {
            throw new ResourceNotFoundException('Product not found');
        }

        return $this->handleView(
            $this->view($product, Response::HTTP_OK)
        );
    }

    /**
     * @IsGranted("ROLE_CUSTOMER")
     */
    public function deleteProductAction($id)
    {
        $product = $this->getDoctrine()
            ->getRepository(Product::class)
            ->find($id);

        if(!$product) {
            throw new ResourceNotFoundException('Product not found');
        }

        try {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($product);
            $entityManager->flush();
        } catch(DoctrineException\ForeignKeyConstraintViolationException $e) {
            return $this->handleView(
                $this->view('', Response::HTTP_CONFLICT)
            );
        }
        
        return $this->handleView(
            $this->view()
        );
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     */
    public function postProductsAction(Request $request)
    {
        $data = json_decode($request->getContent(), true); 

        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);

        $form->submit($data);
        if (false === $form->isValid()) {
            return $this->handleView(
                $this->view($form, Response::HTTP_BAD_REQUEST)
            );
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($product);
        $entityManager->flush();

        return $this->handleView(
            $this->view($product, Response::HTTP_CREATED)
        );
    }
 
    /**
     * @IsGranted("ROLE_ADMIN")
     */
    public function patchProductAction(Request $request, $id)
    {
        $product = $this->getDoctrine()
            ->getRepository(Product::class)
            ->find($id);

        if(!$product) {
            throw new ResourceNotFoundException('Product not found');
        }

        $data = json_decode($request->getContent(), true); 

        $form = $this->createForm(ProductType::class, $product);

        $form->submit($data, false);
        if (false === $form->isValid()) {
            return $this->handleView($this->view($form));
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        $view = $this->view(null, Response::HTTP_NO_CONTENT);
        return $this->handleView(
            $view
        );
    }

    /**
     * @Patch("/products/{id}/price")
     * @IsGranted("ROLE_ADMIN")
     */
    public function updateProductPrice(Request $request, $id)
    {
        $product = $this->getDoctrine()
            ->getRepository(Product::class)
            ->find($id);

        if(!$product) {
            throw new ResourceNotFoundException('Product not found');
        }

        $data = json_decode($request->getContent(), true); 

        $form = $this->createForm(PriceType::class, $product->getPrice());

        $form->submit($data, false);
        if (false === $form->isValid()) {
            return $this->handleView($this->view($form));
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        $view = $this->view(null, Response::HTTP_NO_CONTENT);
        return $this->handleView(
            $view
        );
    }
}
