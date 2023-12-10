<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Form\EditProductType;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class AdministratorController extends AbstractController
{
    public function isAdmin(): RedirectResponse
    {
        $currentUser = $this->getUser();
        $roles = $currentUser->getRoles();
        $isAdmin = false;

        foreach ($roles as $value) {
            if ($value === "ROLE_ADMIN") $isAdmin = true;
        }

        if ($isAdmin === true) {
            return $this->redirectToRoute('app_administrator_products');
        }

        return $this->redirectToRoute('app_client');
    }

    #[Route('/administrator', name: 'app_administrator')]
    public function index(UserRepository $userRepository): Response
    {
        $currentUser = $this->getUser();
        $users = $userRepository->findAll();

        return $this->render('administrator/index.html.twig', [
            'users' => $users,
            'currentUser' => $currentUser,
        ]);
    }

    

    #[Route('/delete-user/{id}', methods: ['GET', 'DELETE'], name: 'app_delete_user')]
    public function deleteUser($id, EntityManagerInterface $em, UserRepository $userRepository): RedirectResponse
    {
        $user = $userRepository->find($id);
        $em->remove($user);
        $em->flush();

        $this->addFlash('deletedUser', 'Delete user with ID '.  $id);

        return $this->redirectToRoute('app_administrator');
    }

    #[Route('/edit-product/{product}', name: 'app_edit_product')]
    public function editProduct(Product $product, EntityManagerInterface $entityManager, Request $request): Response
    {
        $form = $this->createForm(EditProductType::class, $product, ['product_id' => $product->getId()]);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();
            $entityManager->persist($product);
            $entityManager->flush();
            $this->addFlash('productEdited', 'Product with ID ' . $product->getId() . ' is edited!');
            return $this->redirectToRoute('app_administrator_products');
        }

        return $this->render('administrator/edit_product.html.twig', [
            'form' => $form,
        ]);
    }

    // app_edit_order
    #[Route('/edit-order/{order}', name: 'app_edit_order')]
    public function editOrder(Order $order, EntityManagerInterface $entityManager, Request $request): Response
    {
        dd($order);
        // $form = $this->createForm(EditProductType::class, $product, ['product_id' => $product->getId()]);
        // $form->handleRequest($request);
        
        // if ($form->isSubmitted() && $form->isValid()) {
        //     $product = $form->getData();
        //     $entityManager->persist($product);
        //     $entityManager->flush();
        //     $this->addFlash('productEdited', 'Product with ID ' . $product->getId() . ' is edited!');
        //     return $this->redirectToRoute('app_administrator_products');
        // }

        return $this->render('administrator/edit_order.html.twig', [
            // 'form' => $form,
        ]);
    }

}
