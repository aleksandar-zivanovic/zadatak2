<?php

namespace App\Repository;

use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Order>
 *
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    // all orders with their customers and customer profile details
    public function allOrdersWithFullCustomerDetails(): array
    {
        return $this->createQueryBuilder('o')
        ->select('o', 'u', 'up', 'oi')
        ->leftJoin('o.customer', 'u')
        ->leftJoin('u.userProfile', 'up')
        ->join('o.orderedItems', 'oi')
        ->getQuery()
        ->getResult();
    }

    public function ordersIdsFromACustomer(int $id): array
    {
        return $this->createQueryBuilder('o')
        ->select('o.id')
        ->where('o.customer = :id')
        ->setParameter('id', $id)
        ->getQuery()
        ->getScalarResult();
    }

    // all Orders for a single customer with the User and Product details
    public function allOrdersFromSingleCustomer(int $id): array
    {
        return $this->createQueryBuilder('o')
           ->select('o', 'oi', 'p')
           ->leftjoin('o.orderedItems', 'oi')
           ->leftJoin('oi.product', 'p')
           ->where('o.customer = :id')
           ->setParameter('id', $id)
           ->getQuery()
           ->getResult();
    }

//    /**
//     * @return Order[] Returns an array of Order objects
//     */
   public function findOrderWithAllDetails(int $id): array
   {
       return $this->createQueryBuilder('o')
            ->select('o', 'oi', 'p')
            ->leftjoin('o.orderedItems', 'oi')
            ->leftJoin('oi.product', 'p')
            ->andWhere('o.id = :val')
            ->setParameter('val', $id)
            ->getQuery()
            ->getResult()
       ;
   }




//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('o.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Order
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
