<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\SubCategory;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'ecommerce.home', methods: ['GET'])]
    public function index(
        ProductRepository $productRepository,
        CategoryRepository $categoryRepository
    ): Response {

        return $this->render('home/index.html.twig', [
            'products' => $productRepository->findAll(),
            'categories' => $categoryRepository->findAll()
        ]);
    }

    #[Route('/home/show/{id}', name: 'ecommerce.home.show', methods: ['GET'])]
    public function show(Product $product): Response
    {
        return $this->render('home/home.show.html.twig', [
            'product' => $product
        ]);
    }

    #[Route('/home/product/subcategorie/{id}', name: 'ecommerce.product.subcategorie', methods: ['GET'])]
    public function showProductBySubCategorie(SubCategory $subCategory): Response
    {
        $products = $subCategory->getProducts();
        return $this->render('home/product.subcategorie.html.twig', [
            'products' => $products,
            'subcategorie' => $subCategory
        ]);
    }
}