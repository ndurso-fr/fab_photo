<?php

namespace App\Repository;

use App\Entity\Dovetailing;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Dovetailing>
 *
 * @method Dovetailing|null find($id, $lockMode = null, $lockVersion = null)
 * @method Dovetailing|null findOneBy(array $criteria, array $orderBy = null)
 * @method Dovetailing[]    findAll()
 * @method Dovetailing[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DovetailingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Dovetailing::class);
    }

//    /**
//     * @return Dovetailing[] Returns an array of Dovetailing objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Dovetailing
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
