<?php

namespace App\Repository;

use App\Entity\VariantProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method VariantProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method VariantProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method VariantProduct[]    findAll()
 * @method VariantProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VariantProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VariantProduct::class);
    }

    public function getFromProductUuid($uuid) {
        return $this->createQueryBuilder('variantProduct')
            ->join('variantProduct.product', 'product')
            ->where('product.uuid = :uuid')
            ->setParameter(':uuid', $uuid)
            ->getQuery()
            ->getResult()
        ;
    }

    public function getAll($page = 0, $perPage = 20) {
        return $this->createQueryBuilder('vp')
            ->join('vp.product', 'p')
            ->leftJoin('vp.pictures', 'pictures')
            ->setFirstResult($page * $perPage)
            ->setMaxResults($perPage)
            ->getQuery()
            ->getResult()
            ;
    }

    public function getOneByUuid($uuid) {
        return $this->createQueryBuilder('vp')
            ->join('vp.product', 'p')
            ->leftJoin('vp.pictures', 'pictures')
            ->where('vp.id = :uuid')
            ->setParameter('uuid', $uuid)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
