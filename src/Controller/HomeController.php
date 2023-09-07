<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig');
    }

    #[Route('/role-page', name: 'app_role_page')]
    public function rolePage(): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        $currentUser = $this->getUser();
        
        if(in_array('ROLE_ADMIN', $currentUser->getRoles())) {
            // dodati da se redirektuje sa ove stranice, ako nije admin
            return $this->redirectToRoute('app_administrator');
        } elseif(in_array('ROLE_SALESPERSON', $currentUser->getRoles())) {
            // dodati da se redirektuje sa ove stranice, ako nije salersperson
            return $this->redirectToRoute('app_salesperson');
        } elseif(in_array('ROLE_CLIENT', $currentUser->getRoles())) {
            return $this->redirectToRoute('app_client');
        }
    }
}
