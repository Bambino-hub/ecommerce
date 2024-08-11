<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryFormType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


#[Route('admin/categories', name: 'admin.categorie.')]
class CategoryController extends AbstractController
{
    #[Route('/home', name: 'home')]
    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('category/index.html.twig', [
            'categories' => $categoryRepository->findAll()
        ]);
    }


    /**
     * This unction help us to create an category
     *
     * @param Request $request
     * @param EntityManagerInterface $entityManagerInterface
     * @return Response
     */
    #[Route('/create', name: 'create')]
    public function create(
        Request $request,
        EntityManagerInterface $entityManagerInterface
    ): Response {
        $catagory = new Category;
        $form = $this->createForm(CategoryFormType::class, $catagory);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $entityManagerInterface->persist($catagory);
            $entityManagerInterface->flush();

            $this->addFlash('success', 'Catégory create successfully');
            return $this->redirectToRoute('admin.categorie.home');
        }

        return $this->render('category/create.html.twig', [
            'form' => $form
        ]);
    }

    /**
     * this function help us to update category
     */
    #[Route('/update/{id}', name: 'update')]
    public function update(
        Request $request,
        EntityManagerInterface $entityManagerInterface,
        Category $catagory
    ): Response {
        $form = $this->createForm(CategoryFormType::class, $catagory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManagerInterface->flush();

            $this->addFlash('success', 'Catégory update successfully');

            return $this->redirectToRoute('admin.categorie.home');
        }

        return $this->render('category/update.html.twig', [
            'form' => $form
        ]);
    }

    /**
     * this function can delete category
     */
    #[Route('/delete/{id}', name: 'delete')]
    public function delete(

        EntityManagerInterface $entityManagerInterface,
        Category $catagory
    ) {
        $entityManagerInterface->remove($catagory);
        $entityManagerInterface->flush();

        $this->addFlash('success', 'Catégory delete successfully');
        return $this->redirectToRoute('admin.categorie.home');
    }
}