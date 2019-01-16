<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

use FOS\RestBundle\Controller\AbstractFOSRestController;

use App\Entity\Order;
use App\Form\OrderType;

class OrderController extends AbstractFOSRestController
{

    /**
     * @IsGranted("ROLE_CUSTOMER")
     */
    public function getOrderAction($id)
    {
        $order = $this->getDoctrine()
            ->getRepository(Order::class)
            ->find($id);

        if(!$order) {
            throw new ResourceNotFoundException('Order not found');
        }

        return $this->handleView(
            $this->view($order, Response::HTTP_OK)
        );
    }
 
    /**
     * @IsGranted("ROLE_CUSTOMER")
     */
    public function postOrdersAction(Request $request)
    {
        $data = json_decode($request->getContent(), true); 

        $order = new Order();
        $form = $this->createForm(OrderType::class, $order);

        $form->submit($data);
        if (false === $form->isValid()) {
            return $this->handleView(
                $this->view($form, Response::HTTP_BAD_REQUEST)
            );
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($order);
        $entityManager->flush();

        return $this->handleView(
            $this->view($order, Response::HTTP_CREATED)
        );
    }
 
 }

