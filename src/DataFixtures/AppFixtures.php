<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\UserProfile;
use App\Entity\Product;

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
        $user1->setIsVerified(true);
        $manager->persist($user1);

        $user2 = new User();
        $user2->setEmail('zika@example.com');
        $hashed = $this->userPasswordHasherInterface->hashPassword($user1, '123456');
        $user2->setPassword($hashed);
        $user2->setRoles(['ROLE_ADMIN']);
        $user2->setIsVerified(true);
        $manager->persist($user2);

        $user3 = new User();
        $user3->setEmail('sima@example.com');
        $hashed = $this->userPasswordHasherInterface->hashPassword($user1, '123456');
        $user3->setPassword($hashed);
        $user3->setRoles(['ROLE_SALESPERSON']);
        $user3->setIsVerified(true);
        $manager->persist($user3);

        $user4 = new User();
        $user4->setEmail('simona@example.com');
        $hashed = $this->userPasswordHasherInterface->hashPassword($user1, '123456');
        $user4->setPassword($hashed);
        $user4->setRoles(['ROLE_SALESPERSON']);
        $user4->setIsVerified(true);
        $manager->persist($user4);

        $user5 = new User();
        $user5->setEmail('marko@example.com');
        $hashed = $this->userPasswordHasherInterface->hashPassword($user1, '123456');
        $user5->setPassword($hashed);
        $user5->setRoles(['ROLE_CLIENT']);
        $user5->setIsVerified(true);
        $manager->persist($user5);

        $user6 = new User();
        $user6->setEmail('marija@example.com');
        $hashed = $this->userPasswordHasherInterface->hashPassword($user1, '123456');
        $user6->setPassword($hashed);
        $user6->setRoles(['ROLE_CLIENT']);
        $user6->setIsVerified(true);
        $manager->persist($user6);

        $profile1 = new UserProfile();
        $profile1->setUserId($user1);
        $profile1->setUserName('Mika');
        $profile1->setAddress('Mihajla Pupina 123456');
        $profile1->setCountry('Srbija');
        $profile1->setPhone('+38163123456');
        $manager->persist($profile1);

        $profile2 = new UserProfile();
        $profile2->setUserId($user2);
        $profile2->setUserName('Zika');
        $profile2->setAddress('Zikice Jovanovica Spanca 123456');
        $profile2->setCountry('Spanija');
        $manager->persist($profile2);

        $profile3 = new UserProfile();
        $profile3->setUserId($user3);
        $profile3->setUserName('Sima');
        $profile3->setAddress('Sima Milutinović Sarajlija 123456');
        $profile3->setCountry('Bosna i Hercegovina');
        $manager->persist($profile3);

        $profile4 = new UserProfile();
        $profile4->setUserId($user4);
        $profile4->setUserName('Simona');
        $profile4->setAddress('Simonide Paleolog Nemanjić 123456');
        $profile4->setCountry('Srbija');
        $manager->persist($profile4);

        $profile5 = new UserProfile();
        $profile5->setUserId($user5);
        $profile5->setUserName('Marko');
        $profile5->setAddress('Kraljevica Marka 123456');
        $profile5->setCountry('Srbija');
        $manager->persist($profile5);

        $profile6 = new UserProfile();
        $profile6->setUserId($user6);
        $profile6->setUserName('Marija');
        $profile6->setAddress('Marije Kiri 123456');
        $profile6->setCountry('Francuska');
        $manager->persist($profile6);

        // products

        $product1 = new Product();
        $product1->setName('Monitor');
        $product1->setPrice('30000');
        $product1->setUnit('piece');
        $manager->persist($product1);

        $product2 = new Product();
        $product2->setName('Apple');
        $product2->setPrice('150');
        $product2->setUnit('kg');
        $manager->persist($product2);

        $product3 = new Product();
        $product3->setName('Coca Cola');
        $product3->setPrice('200');
        $product3->setUnit('bottle');
        $manager->persist($product3);

        
        $product4 = new Product();
        $product4->setName('Gas 95');
        $product4->setPrice('187');
        $product4->setUnit('liter');
        $manager->persist($product4);
        
        $manager->flush();
    }
}
