<?php

namespace App\Repository;

use App\Entity\AccessImage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AccessImage>
 *
 * @method AccessImage|null find($id, $lockMode = null, $lockVersion = null)
 * @method AccessImage|null findOneBy(array $criteria, array $orderBy = null)
 * @method AccessImage[]    findAll()
 * @method AccessImage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccessImageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AccessImage::class);
    }

//    /**
//     * @return AccessImage[] Returns an array of AccessImage objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?AccessImage
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
