<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\ProductHIstry;
use App\Form\ProductHistryFormType;
use App\Form\ProductType;
use App\Repository\ProductHIstryRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/product')]
class ProductController extends AbstractController
{
    #[Route('/', name: 'app_product_index', methods: ['GET'])]
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('product/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_product_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $produchistry = new ProductHIstry;
            $produchistry->setProduct($product)
                ->setQte($product->getStock())
                ->setCreatedAt(new \DateTimeImmutable());

            $entityManager->persist($product);
            $entityManager->persist($produchistry);

            $entityManager->flush();
            $this->addFlash('success', "votre produit a été crée");

            return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('product/new.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_product_show', methods: ['GET'])]
    public function show(Product $product): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_product_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', "votre produit a été modifié");
            return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_product_delete', methods: ['POST'])]
    public function delete(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $product->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($product);
            $entityManager->flush();
            $this->addFlash('danger', "votre produit a été supprimer");
        }

        return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/add/stock/{id}', name: 'app_add_stock', methods: ['GET', 'POST'])]
    public function addStock(
        Request $request,
        Product $product,
        EntityManagerInterface $entityManager
    ): Response {
        $productHistry = new ProductHIstry();
        $form = $this->createForm(ProductHistryFormType::class, $productHistry);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            if ($form->get('qte')->getData() > 0) {
                $newStock = $form->get('qte')->getData() + $product->getStock();

                $product->setStock($newStock);

                $productHistry->setProduct($product)
                    ->setQte($form->get('qte')->getData())
                    ->setCreatedAt(new \DateTimeImmutable());

                $entityManager->persist($productHistry);
                $entityManager->flush();

                $this->addFlash('success', "le stock a été bien Ajouter");
                return $this->redirectToRoute("app_product_index");
            } else {
                $this->addFlash('warning', 'stock invalid');
                return $this->redirectToRoute('app_add_stock', ['id' => $product->getId()]);
            }
        }
        return $this->render('product/stock.html.twig', [
            'form' => $form,
            'product' => $product
        ]);
    }

    #[Route('/histry/{id}', name: 'app_histry', methods: ['GET'])]
    public function productHistry(
        Product $product,
        ProductHIstryRepository $productHIstryRepository
    ): Response {
        $histry =  $productHIstryRepository->findBy(['product' => $product]);

        return $this->render('product/histry.html.twig', [
            'histrys' => $histry
        ]);
    }
}
