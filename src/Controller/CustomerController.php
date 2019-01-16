<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

use FOS\RestBundle\Controller\AbstractFOSRestController;

use App\Entity\Customer;
use App\Form\CustomerType;

class CustomerController extends AbstractFOSRestController
{
    /**
     * @IsGranted("ROLE_ADMIN")
     */
    public function getCustomersAction()
    {
        $customers = $this->getDoctrine()
            ->getRepository(Customer::class)
            ->findAll();
   
        $data = ['customers' => $customers];
        
        return $this->handleView(
            $this->view($data, Response::HTTP_OK)
        );
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     */
    public function getCustomerAction($id)
    {
        $customer = $this->getDoctrine()
            ->getRepository(Customer::class)
            ->find($id);

        if(!$customer) {
            throw new ResourceNotFoundException('Customer not found');
        }

        return $this->handleView(
            $this->view($customer, Response::HTTP_OK)
        );
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     */
    public function deleteCustomerAction($id)
    {
        $customer = $this->getDoctrine()
            ->getRepository(Customer::class)
            ->find($id);

        if(!$customer) {
            throw new ResourceNotFoundException('Customer not found');
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($customer);
        $entityManager->flush();
        
        return $this->handleView(
            $this->view()
        );
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     */
    public function postCustomersAction(Request $request)
    {
        $data = json_decode($request->getContent(), true); 

        $customer = new Customer();
        $form = $this->createForm(CustomerType::class, $customer);

        $form->submit($data);
        if (false === $form->isValid()) {
            return $this->handleView(
                $this->view($form, Response::HTTP_BAD_REQUEST)
            );
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($customer);
        $entityManager->flush();

        return $this->handleView(
            $this->view($customer, Response::HTTP_CREATED)
        );
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     */
    public function patchCustomerAction(Request $request, $id)
    {
        $customer = $this->getDoctrine()
            ->getRepository(Customer::class)
            ->find($id);

        if(!$customer) {
            throw new ResourceNotFoundException('Customer not found');
        }

        $data = json_decode($request->getContent(), true); 

        $form = $this->createForm(CustomerType::class, $customer);

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
