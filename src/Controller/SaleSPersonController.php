<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_SALESPERSON')]
class SaleSPersonController extends AbstractController
{
    #[Route('/salesperson', name: 'app_salesperson')]
    public function index(): Response
    {
        return $this->render('salesperson/index.html.twig', [
            'controller_name' => 'SaleSPersonController',
        ]);
    }
}
