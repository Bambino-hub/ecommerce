<?php

namespace App\Service;

use App\Repository\ProductRepository;

class Cart
{
    public function __construct(private readonly ProductRepository $productRepository) {}

    public function getCart($session): array
    {
        $cart = $session->get('cart');
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

        return [
            'cart' => $CartWithData,
            'total' => $total
        ];
    }
}
