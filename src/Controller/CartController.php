<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

class CartController extends AbstractController
{
    public function __construct(private readonly ProductRepository $productRepository) {}


    #[Route('/cart', name: 'app_cart', methods: ['GET'])]
    public function index(SessionInterface $sessionInterface): Response
    {
        $cart = $sessionInterface->get('cart');
        $CartWithData = [];

        foreach ($cart as $id => $quantity) {
            $CartWithData[] = [
                'product'  => $this->productRepository->find($id),
                'quantity' => $quantity
            ];
        }

        $total = array_sum(array_map(function ($items) {
            return $items['product']->getPrice() * $items['quantity'];
        }, $CartWithData));

        return $this->render('cart/index.html.twig', [
            'cardWithDatas' => $CartWithData,
            'total' => $total
        ]);
    }


    #[Route('/add/cart/{id}', name: 'app_add_to_cart', methods: ['GET'])]
    public function addToCart($id, SessionInterface $sessionInterface)
    {
        $cart = $sessionInterface->get('cart');
        if (!empty($cart[$id])) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }

        $sessionInterface->set('cart', $cart);

        return $this->redirectToRoute('app_cart');
    }


    #[Route('/remove/cart/{id}', name: 'app_remove_to_cart', methods: ['GET'])]
    public function removeTocart($id, SessionInterface $sessionInterface)
    {
        $cart = $sessionInterface->get('cart');
        if (!empty($cart[$id])) {
            unset($cart[$id]);
        }

        $sessionInterface->set('cart', $cart);
        return $this->redirectToRoute('app_cart');
    }

    #[Route('/remove', name: 'app_remove_cart', methods: ['GET'])]
    public function remove(SessionInterface $sessionInterface)
    {
        $sessionInterface->set('cart', []);

        return $this->redirectToRoute('app_cart');
    }
}