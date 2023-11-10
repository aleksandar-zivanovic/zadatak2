<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderedItem;
use App\Entity\Product;
use App\Entity\User;
use App\Entity\UserProfile;
use App\Form\EditUserType;
use App\Form\ProfileType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Form\EditProductType;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
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

    #[Route('/administrator/orders', name: 'app_administrator_orders')]
    public function orders(OrderRepository $orderRepository): Response
    {
        $orders = $orderRepository->allOrdersWithFullCustomerDetails();
        $currentUser = $this->getUser();

        // foreach ($orders as $key => $value) {
        //     dd($value->orderedItems[0]->quantity);
        // }

        return $this->render('administrator/orders_administration.html.twig', [
            'orders' => $orders,
            'currentUser' => $currentUser,
        ]);
    }



    #[Route('/administrator/orders/{id}', name: 'app_administrator_order_details')]
    public function orderDetails(
        $id, 
        OrderRepository $orderRepository,
        EntityManagerInterface $entityManager
        ): Response
    {
        $order = $orderRepository->find(1);
        dd($order);

        // $orderDetails = $entityManager->getRepository(Order::class)->find(1);
        // dd($id);

        // $orders = $orderRepository->allOrdersWithFullCustomerDetails();
        $currentUser = $this->getUser();
        return $this->render('administrator/order_details_administration.html.twig', [
            // 'order' => $order,
            // 'orders' => $orders,
            'currentUser' => $currentUser,
        ]);
    }

    #[Route('/administrator/products', name: 'app_administrator_products')]
    public function products(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAll();
        $currentUser = $this->getUser();

        return $this->render('administrator/product_administration.html.twig', [
            'products' => $products,
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

    #[Route('/edit-user/{id}', name: 'app_edit_user')]
    public function editUser($id, EntityManagerInterface $entityManager, Request $request): Response
    {

        $user = $entityManager->getRepository(User::class)->find($id);
        $form = $this->createForm(EditUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('userEdited', 'User details are updated!');
            return $this->redirectToRoute('app_administrator');
        }

        return $this->render('administrator/edit_user.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/edit-profile/{id}', name: 'app_edit_user_profile')]
    public function editProfile($id, EntityManagerInterface $entityManager, Request $request): Response
    {
        $user = $entityManager->find(User::class, $id);
        $userProfile = $user->getUserProfile();
        $form = $this->createForm(ProfileType::class, $userProfile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userProfile = $form->getData();
            $entityManager->persist($userProfile);
            $entityManager->flush();
            $this->addFlash('userProfileEdited', 'User secondary information is edited!');
            return $this->redirectToRoute('app_administrator');
        }

        return $this->render('administrator/edit_user.html.twig', [
            'form' => $form,
        ]);
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
