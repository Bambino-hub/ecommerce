<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderProduct;
use App\Form\OrderType;
use App\Repository\CityRepository;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use App\Service\Cart;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderController extends AbstractController
{
    #[Route('/order', name: 'app_order')]
    public function index(
        Request $request,
        SessionInterface $sessionInterface,
        ProductRepository $productRepository,
        EntityManagerInterface $entityManagerInterface,
        Cart $cart
    ): Response {
        $order = new Order();
        $form = $this->createForm(OrderType::class, $order);

        $form->handleRequest($request);
        $data = $cart->getCart($sessionInterface);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($order->isPayOnDeliver()) {

                if (!empty($data['cart'])) {


                    $order->setTotalPrice($data['total']);
                    $order->setCreatedAt(new \DateTimeImmutable());

                    $entityManagerInterface->persist($order);
                    $entityManagerInterface->flush();

                    foreach ($data['cart'] as $value) {
                        $orderProduct = new OrderProduct();
                        $orderProduct->setOrder($order);

                        $orderProduct->setProduct($value['product']);
                        $orderProduct->setQte($value['quantity']);

                        $entityManagerInterface->persist($orderProduct);
                        $entityManagerInterface->flush();
                    }
                }
            }

            $sessionInterface->set('cart', []);
            $this->addFlash('success', "votre commande à été envoyer");
            return $this->redirectToRoute('ecommerce.home');
        }

        return $this->render('order/order.html.twig', [
            'form' => $form,
            'total' => $data['total'],
        ]);
    }


    #[Route('/order/all', name: 'app_order_all')]
    public function getAllOrder(OrderRepository $orderRepository): Response
    {

        return $this->render('order/all.order.html.twig', [
            'orders' => $orderRepository->findAll()
        ]);
    }

    #[Route('/order/{id}/iscompleted/update', name: 'app_iscompleted_update')]
    public function updateIsCompleted(Order $order, EntityManagerInterface $entityManagerInterface)
    {
        $order->setCompleted(true);
        $entityManagerInterface->flush();
        $this->addFlash('success', "la commande a été livrée avec success");
        return $this->redirectToRoute('app_order_all');
    }

    #[Route('/order/{id}/remove', name: 'app_order_remove')]
    public function orderRemove(Order $order, EntityManagerInterface $entityManagerInterface)
    {
        $entityManagerInterface->remove($order);
        $entityManagerInterface->flush();
        $this->addFlash('danger', "la commande a été supprimé");
        return $this->redirectToRoute('app_order_all');
    }
}
