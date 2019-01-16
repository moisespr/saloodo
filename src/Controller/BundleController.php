<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\Patch;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Delete;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

use Doctrine\DBAL\Exception as DoctrineException;

use Swagger\Annotations as SWG;

use App\Form\PriceType;
use App\Form\ProductType;
use App\Form\BundleType;

use App\Entity\Product;
use App\Entity\Bundle;

class BundleController extends AbstractFOSRestController
{
    /**
     * @IsGranted("ROLE_CUSTOMER")
     * @SWG\Response(
     *  response=200,
     *  description="List all bundles"
     * )
     * @SWG\Tag(name="bundles")
     */
    public function getBundlesAction()
    {
        $bundles = $this->getDoctrine()
            ->getRepository(Bundle::class)
            ->findAll();
   
        $data = ['bundles' => $bundles];
        
        return $this->handleView(
            $this->view($data, Response::HTTP_OK)
        );
    }

    /**
     * @IsGranted("ROLE_CUSTOMER")
     * @SWG\Response(
     *  response=200,
     *  description="Retrieve one bundle by ID."
     * )
     * @SWG\Tag(name="bundles")
     */
    public function getBundleAction($id)
    {
        $bundle = $this->getDoctrine()
            ->getRepository(Bundle::class)
            ->find($id);

        if(!$bundle) {
            throw new ResourceNotFoundException('Bundle not found');
        }

        return $this->handleView(
            $this->view($bundle, Response::HTTP_OK)
        );
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @SWG\Response(
     *  response=200,
     *  description="Deletes one bundle by ID."
     * )
     * @SWG\Tag(name="bundles")
     */
    public function deleteBundleAction($id)
    {
        $bundle = $this->getDoctrine()
            ->getRepository(Bundle::class)
            ->find($id);

        if(!$bundle) {
            throw new ResourceNotFoundException('Bundle not found');
        }

        try {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($bundle);
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
     * @SWG\Response(
     *  response=201,
     *  description="Creates one bundle. The products list should contains only integers representing valid product's IDs."
     * )
     * @SWG\Tag(name="bundles")
     */
    public function postBundlesAction(Request $request)
    {
        $data = json_decode($request->getContent(), true); 

        $bundle = new Bundle();
        $form = $this->createForm(BundleType::class, $bundle);

        $form->submit($data);
        if (false === $form->isValid()) {
            return $this->handleView(
                $this->view($form, Response::HTTP_BAD_REQUEST)
            );
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($bundle);
        $entityManager->flush();

        return $this->handleView(
            $this->view($bundle, Response::HTTP_CREATED)
        );
    }
 
    /**
     * @IsGranted("ROLE_ADMIN")
     * @SWG\Response(
     *  response=204,
     *  description="Updates one bundle by ID."
     * )
     * @SWG\Tag(name="bundles")
     */
    public function patchBundleAction(Request $request, $id)
    {
        $bundle = $this->getDoctrine()
            ->getRepository(Bundle::class)
            ->find($id);

        if(!$bundle) {
            throw new ResourceNotFoundException('Bundle not found');
        }

        $data = json_decode($request->getContent(), true);

        $form = $this->createForm(BundleType::class, $bundle);

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
     * @Patch("/bundles/{id}/price")
     * @IsGranted("ROLE_ADMIN")
     * @SWG\Response(
     *  response=204,
     *  description="Updates bundle's price or discount by ID."
     * )
     * @SWG\Tag(name="bundles")
     */
    public function updateBundlePrice(Request $request, $id)
    {
        $bundle = $this->getDoctrine()
            ->getRepository(Bundle::class)
            ->find($id);

        if(!$bundle) {
            throw new ResourceNotFoundException('Bundle not found');
        }

        $data = json_decode($request->getContent(), true); 

        $form = $this->createForm(PriceType::class, $bundle->getPrice());

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
     * @Post("/bundles/{id}/products")
     * @IsGranted("ROLE_ADMIN")
     * @SWG\Response(
     *  response=204,
     *  description="Add products to bundle by ID. The products list should contains only integers representing valid product's IDs."
     * )
     * @SWG\Tag(name="bundles")
     */
    public function addProductsToBundle(Request $request, $id)
    {
        $bundle = $this->getDoctrine()
            ->getRepository(Bundle::class)
            ->find($id);

        if(!$bundle) {
            throw new ResourceNotFoundException('Bundle not found');
        }

        $data = json_decode($request->getContent(), true);

        if(!$this->dataHasProductsArray($data)) {
            return $this->handleView(
                $this->view(null, Response::HTTP_BAD_REQUEST)
            );
        }

        $products = $data['products'];
        $added = false;
        foreach($products as $productId) {
            if($productId == $id) 
                continue;
            $product = $this->getDoctrine()
                ->getRepository(Product::class)
                ->find($productId);
            if($product) {
                $bundle->addProduct($product);
                $added = true;
            }
        }

        if(!$added) {
            return $this->handleView(
                $this->view(null, Response::HTTP_BAD_REQUEST)
            );
        }

        try {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
        } catch(DoctrineException\UniqueConstraintViolationException $e) {
            return $this->handleView(
                $this->view('', Response::HTTP_CONFLICT)
            );
        }

        $view = $this->view(null, Response::HTTP_NO_CONTENT);
        return $this->handleView(
            $view
        ); 
    }

    /**
     * @Delete("/bundles/{id}/products")
     * @IsGranted("ROLE_ADMIN")
     * @SWG\Response(
     *  response=204,
     *  description="Deletes product from bundle by ID."
     * )
     * @SWG\Tag(name="bundles")
     */
    public function deleteProductsFromBundle(Request $request, $id)
    {
        $bundle = $this->getDoctrine()
            ->getRepository(Bundle::class)
            ->find($id);

        if(!$bundle) {
            throw new ResourceNotFoundException('Bundle not found');
        }

        $data = json_decode($request->getContent(), true);
        
        if(!$this->dataHasProductsArray($data)) {
            return $this->handleView(
                $this->view(null, Response::HTTP_BAD_REQUEST)
            );
        }

        $products = $data['products'];
        foreach($products as $productId) {
            $product = $this->getDoctrine()
                ->getRepository(Product::class)
                ->find($productId);
            $bundle->removeProduct($product);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        $view = $this->view(null, Response::HTTP_NO_CONTENT);
        return $this->handleView(
            $view
        ); 
    }

    private function dataHasProductsArray($data)
    {
        return array_key_exists('products', $data) 
            && is_array($data['products']) 
            && !empty($data['products']);
    }
}

