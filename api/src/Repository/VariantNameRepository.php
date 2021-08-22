<?php

namespace App\Repository;

use App\Entity\VariantName;
use App\Model\Shop;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method VariantName|null find($id, $lockMode = null, $lockVersion = null)
 * @method VariantName|null findOneBy(array $criteria, array $orderBy = null)
 * @method VariantName[]    findAll()
 * @method VariantName[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VariantNameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VariantName::class);
    }



    /*
     * @deprecated since new repartition of variant TODO check if this is really deprecated.
     */
    public function getVariantsForUuid($uuid) {
        return $this->createQueryBuilder('variantName')
            ->join('variantName.products', 'product')
            ->where('product.uuid = :uuid')
            ->setParameter('uuid', $uuid)
            ->leftJoin('variantName.variantLabels', 'variantLabels')
            ->orderBY('variantLabels.rank', 'ASC')
            ->orderBy('variantName.rank', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }


    public function getVariantsForShop(\App\Entity\Shop $shop, $query = null) {
        $qb = $this->createQueryBuilder('variantName')
            ->join('variantName.shop', 'shop')
            ->where('shop.uuid = :uuid')
            ->setParameter('uuid', $shop->getUuid())
            ->leftJoin('variantName.variantLabels', 'variantLabels');

        if($query) {
            $qb = $qb
                ->andWhere('variantName.name LIKE :query')
                ->setParameter('query', '%'. $query. '%');
        }
        return $qb
            ->orderBY('variantLabels.rank', 'ASC')
            ->orderBy('variantName.rank', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    // /**
    //  * @return VariantName[] Returns an array of VariantName objects
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
    public function findOneBySomeField($value): ?VariantName
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
