<?php

namespace App\Repository;

use App\Entity\OrdersHistory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OrdersHistory>
 *
 * @method OrdersHistory|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrdersHistory|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrdersHistory[]    findAll()
 * @method OrdersHistory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrdersHistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrdersHistory::class);
    }

//    /**
//     * @return OrdersHistory[] Returns an array of OrdersHistory objects
//     */
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

//    public function findOneBySomeField($value): ?OrdersHistory
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
