<?php

namespace App\Controller;

use App\Entity\Order;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BillController extends AbstractController
{
    #[Route('/order/{id}/bill', name: 'app_bill')]
    public function index(Order $order): Response
    {
        return $this->render('bill/index.html.twig', [
            'order' => $order,
        ]);
    }
}
