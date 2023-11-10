<?php

namespace App\DataFixtures;

use App\Entity\Order;
use App\Entity\OrderedItem;
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
        $product1->setName('LG Ultragear 31.5" VA 32GN650-B Monitor');
        $product1->setCategory('Monitor');
        $product1->setPrice('35000');
        $product1->setUnit('piece');
        $manager->persist($product1);

        $product2 = new Product();
        $product2->setName('LG 27" IPS 27UP650-W Monitor');
        $product2->setCategory('Monitor');
        $product2->setPrice('50000');
        $product2->setUnit('piece');
        $manager->persist($product2);

        $product3 = new Product();
        $product3->setName('LG 31.5" IPS 32UN650P-W Monitor');
        $product3->setCategory('Monitor');
        $product3->setPrice('51000');
        $product3->setUnit('piece');
        $manager->persist($product3);
        
        $product4 = new Product();
        $product4->setName('LG OLED55C21LA SMART');
        $product4->setCategory('tv');
        $product4->setPrice('150000');
        $product4->setUnit('piece');
        $manager->persist($product4);

        $product5 = new Product();
        $product5->setName('Fanta');
        $product5->setCategory('Beverage');
        $product5->setPrice('180');
        $product5->setUnit('bottle');
        $manager->persist($product5);

        $product6 = new Product();
        $product6->setName('Sprite');
        $product6->setCategory('Beverage');
        $product6->setPrice('190');
        $product6->setUnit('bottle');
        $manager->persist($product6);
        
        $product7 = new Product();
        $product7->setName('Apple');
        $product7->setCategory('Fruit');
        $product7->setPrice('150');
        $product7->setUnit('kg');
        $manager->persist($product7);
        
        $product8 = new Product();
        $product8->setName('Orange');
        $product8->setCategory('Fruit');
        $product8->setPrice('180');
        $product8->setUnit('kg');
        $manager->persist($product8);

        // orders

        $order1 = new Order();
        $orderedItem1 = new OrderedItem();
        $orderedItem1->setRelatedOrder($order1);
        $orderedItem1->setProduct($product1);
        $orderedItem1->setQuantity(3);
        $orderedItem1->setCreatedAt(new \DateTime());
        $orderedItem1->setPricePerPiece($product1->getPrice());
        $order1->setCustomer($user5);
        $order1->setComment("This is a comment for order 1");
        $order1->setStatus("processing");
        $order1->setCreatedAt(new \DateTimeImmutable());
        $manager->persist($orderedItem1);
        $manager->persist($order1);

        $order2 = new Order();
        $orderedItem2 = new OrderedItem();
        $orderedItem2->setRelatedOrder($order2);
        $orderedItem2->setProduct($product4);
        $orderedItem2->setQuantity(1);
        $orderedItem2->setCreatedAt(new \DateTime());
        $orderedItem2->setPricePerPiece($product4->getPrice());
        $orderedItem3 = new OrderedItem();
        $orderedItem3->setRelatedOrder($order2);
        $orderedItem3->setProduct($product7);
        $orderedItem3->setQuantity(8);
        $orderedItem3->setCreatedAt(new \DateTime());
        $orderedItem3->setPricePerPiece($product7->getPrice());
        $order2->setCustomer($user6);
        $order2->setComment("This is a comment for order 2");
        $order2->setStatus("processing");
        $order2->setCreatedAt(new \DateTimeImmutable());
        $manager->persist($orderedItem2);
        $manager->persist($orderedItem3);
        $manager->persist($order2);

        $order3 = new Order();
        $orderedItem4 = new OrderedItem();
        $orderedItem4->setRelatedOrder($order3);
        $orderedItem4->setProduct($product7);
        $orderedItem4->setQuantity(1);
        $orderedItem4->setCreatedAt(new \DateTime());
        $orderedItem4->setPricePerPiece($product4->getPrice());
        $orderedItem5 = new OrderedItem();
        $orderedItem5->setRelatedOrder($order3);
        $orderedItem5->setProduct($product8);
        $orderedItem5->setQuantity(2);
        $orderedItem5->setCreatedAt(new \DateTime());
        $orderedItem5->setPricePerPiece($product7->getPrice());
        $order3->setCustomer($user5);
        $order3->setComment("This is a comment for order 3");
        $order3->setStatus("processing");
        $order3->setCreatedAt(new \DateTimeImmutable());
        $manager->persist($orderedItem4);
        $manager->persist($orderedItem5);
        $manager->persist($order3);

        $manager->flush();
    }
}
