<?php

namespace App\Repository;

use App\Entity\VariantRemoved;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method VariantRemoved|null find($id, $lockMode = null, $lockVersion = null)
 * @method VariantRemoved|null findOneBy(array $criteria, array $orderBy = null)
 * @method VariantRemoved[]    findAll()
 * @method VariantRemoved[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VariantRemovedRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VariantRemoved::class);
    }


    public function findByProduct($uuid) {
        return $this->createQueryBuilder('vr')
            ->join('vr.product', 'product')
            ->where('product.uuid = :uuid')
            ->setParameter('uuid', $uuid)
            ->getQuery()
            ->getResult()
        ;
    }
    // /**
    //  * @return VariantRemoved[] Returns an array of VariantRemoved objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?VariantRemoved
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
