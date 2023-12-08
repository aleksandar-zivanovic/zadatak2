<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_SALESPERSON')]
class SaleSPersonController extends AbstractController
{
    #[Route('/salesperson', name: 'app_salesperson')]
    public function index(UserRepository $userRepository): Response
    {

        $currentUser = $this->getUser();
        $users = $userRepository->findAllByRole("ROLE_CLIENT");
        return $this->render('salesperson/index.html.twig', [
            'users' => $users,
            'currentUser' => $currentUser,
        ]);
    }
}
