<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use App\Form\EditUserType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Repository\ProductRepository;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig');
    }

    #[Route('/access-denied', name: 'app_access_denied')]
    public function accessDenied(): Response
    {
        return $this->render('home/access_denied.html.twig');
    }

    #[Route('/role-page', name: 'app_role_page')]
    public function rolePage(): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        $currentUser = $this->getUser();
        
        if(in_array('ROLE_ADMIN', $currentUser->getRoles())) {
            return $this->redirectToRoute('app_administrator');
        } elseif(in_array('ROLE_SALESPERSON', $currentUser->getRoles())) {
            return $this->redirectToRoute('app_salesperson');
        } elseif(in_array('ROLE_CLIENT', $currentUser->getRoles())) {
            return $this->redirectToRoute('app_client');
        }
    }

    #[Route('/edit-user/{id}', name: 'app_edit_user')]
    #[IsGranted('IS_AUTHENTICATED')]
    public function editUser($id, EntityManagerInterface $entityManager, Request $request): Response|AccessDeniedException
    {
        $currentUser = $this->getUser();
        if (!in_array(haystack:$currentUser->getRoles(), needle:"ROLE_ADMIN") && $currentUser->getId() != $id) {
            throw new AccessDeniedException();
        }
        
        $user = $entityManager->getRepository(User::class)->find($id);
        $form = $this->createForm(EditUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('userEdited', 'User details are updated!');
            
            return $this->redirectToRoute('app_role_page');
        }

        return $this->render('administrator/edit_user.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/home/products', name: 'app_home_products')]
    public function products(ProductRepository $productRepository): Response
    {
        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_SALESPERSON')) {
            throw new AccessDeniedException();
        }

        $products = $productRepository->findAll();
        $currentUser = $this->getUser();

        return $this->render('home/product_administration.html.twig', [
            'products' => $products,
            'currentUser' => $currentUser,
        ]);
    }
}
