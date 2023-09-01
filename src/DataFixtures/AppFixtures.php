<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $userPasswordHasherInterface;

    public function __construct (UserPasswordHasherInterface $userPasswordHasherInterface) 
    {
        $this->userPasswordHasherInterface = $userPasswordHasherInterface;
    }

    public function load(ObjectManager $manager): void
    {
        $user1 = new User();
        $user1->setEmail('mika@example.com');
        $hashed = $this->userPasswordHasherInterface->hashPassword($user1, '123456');
        $user1->setPassword($hashed);
        $user1->setRoles(['ROLE_ADMIN']);
        $manager->persist($user1);

        $user2 = new User();
        $user2->setEmail('zika@example.com');
        $hashed = $this->userPasswordHasherInterface->hashPassword($user1, '123456');
        $user2->setPassword($hashed);
        $user2->setRoles(['ROLE_ADMIN']);
        $manager->persist($user2);

        $user3 = new User();
        $user3->setEmail('sima@example.com');
        $hashed = $this->userPasswordHasherInterface->hashPassword($user1, '123456');
        $user3->setPassword($hashed);
        $user3->setRoles(['ROLE_SALESPERSON']);
        $manager->persist($user3);

        $user4 = new User();
        $user4->setEmail('simona@example.com');
        $hashed = $this->userPasswordHasherInterface->hashPassword($user1, '123456');
        $user4->setPassword($hashed);
        $user4->setRoles(['ROLE_SALESPERSON']);
        $manager->persist($user4);

        $user5 = new User();
        $user5->setEmail('marko@example.com');
        $hashed = $this->userPasswordHasherInterface->hashPassword($user1, '123456');
        $user5->setPassword($hashed);
        $user5->setRoles(['ROLE_CLIENT']);
        $manager->persist($user5);

        $user6 = new User();
        $user6->setEmail('marija@example.com');
        $hashed = $this->userPasswordHasherInterface->hashPassword($user1, '123456');
        $user6->setPassword($hashed);
        $user6->setRoles(['ROLE_CLIENT']);
        $manager->persist($user6);

        $manager->flush();
    }
}
