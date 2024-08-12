<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('ecommerce', name: 'ecommerce.')]

class UsersController extends AbstractController
{
    #[Route('/users', name: 'users')]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('users/user.html.twig', [
            'users' => $userRepository->findAll()
        ]);
    }

    #[Route('/{id}/set/role', name: 'role', requirements: ['id' => Requirement::DIGITS])]
    public function setRole(EntityManagerInterface $entityManagerInterface, User $user)
    {
        $user->setRoles(["ROLE_EDITOR", "ROLE_USER"]);
        $entityManagerInterface->flush();

        $this->addFlash('success', "role set successfully");
        return $this->redirectToRoute('ecommerce.users');
    }


    #[Route('/{id}/remove/role', name: 'role.remove', requirements: ['id' => Requirement::DIGITS])]
    public function removeRole(EntityManagerInterface $entityManagerInterface, User $user)
    {
        $user->setRoles([]);
        $entityManagerInterface->flush();

        $this->addFlash('danger', "role removed successfully");
        return $this->redirectToRoute('ecommerce.users');
    }

    #[Route('/{id}/remove/user', name: 'user.remove', requirements: ['id' => Requirement::DIGITS])]
    public function removeUser(EntityManagerInterface $entityManagerInterface, User $user)
    {
        $entityManagerInterface->remove($user);
        $entityManagerInterface->flush();

        $this->addFlash('danger', "user removed successfully");
        return $this->redirectToRoute('ecommerce.users');
    }
}
