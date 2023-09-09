<?php

namespace App\Controller;

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

class AdministratorController extends AbstractController
{
    #[Route('/administrator', name: 'app_administrator')]
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        return $this->render('administrator/index.html.twig', [
            'users' => $users,
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

    #[Route('/edit-user/{user}', name: 'app_edit_user')]
    public function editUser(User $user, EntityManagerInterface $entityManager, Request $request): Response
    {
        $form = $this->createForm(EditUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('userEdited', 'User data is edited!');
            return $this->redirectToRoute('app_administrator');
        }

        return $this->render('administrator/edit_user.html.twig', [
            'form' => $form,
            'user' => $user,
        ]);
    }

    #[Route('/edit-profile/{id}', name: 'app_edit_user')]
    public function editProfile($id, EntityManagerInterface $entityManager, Request $request, UserRepository $userRepository): Response
    {
        // $user = $entityManager->getRepository(User::class)->find($id);
        $user = $userRepository->find($id);
        $userProfile = $user->getUserProfile();
        
        // dd($userProfile);
        $form = $this->createForm(ProfileType::class, $userProfile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userProfile = $form->getData();
            $entityManager->persist($userProfile);
            $entityManager->flush();
            $this->addFlash('userEdited', 'Profile is edited!');
            return $this->redirectToRoute('app_administrator');
        }

        return $this->render('administrator/edit_user.html.twig', [
            'form' => $form,
        ]);
    }
}