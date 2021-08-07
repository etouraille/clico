<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, LoggerInterface $logger)
    {
        $this->logger = $logger;
        parent::__construct($registry, Product::class);
    }

    public function getProducstByShopUuid($uuid) {
        return $this->createQueryBuilder('p')
            ->join('p.shop', 'shop')
            ->leftJoin('p.pictures', 'pictures')
            ->where('shop.uuid = :uuid')
            ->setParameter('uuid', $uuid)
            ->getQuery()
            ->getResult()
        ;

    }

    public function getProductByShopUuidAndFilterPaginate(
        $uuid,
        $filter= '',
        $orderBy = 'asc',
        $pageNumber = 0,
        $pageSize = 10
    )
    {

        $qb = $this->createQueryBuilder('p');

        $qb->select('count(p)')
            ->join('p.shop', 'shop')
            ->where('shop.uuid = :uuid')
            ->setParameter('uuid', $uuid);

        $n = $this->addFilter($qb, $filter, $orderBy, $pageNumber, $pageSize)
            ->getQuery()
            ->getSingleScalarResult();

        $pages = floor($n / $pageSize) + ($n % $pageSize > 0 ? 1 : 0);

        $qb = $this->createQueryBuilder('p')
            ->join('p.shop', 'shop')
            ->leftJoin('p.pictures', 'pictures')
            ->where('shop.uuid = :uuid')
            ->setParameter('uuid', $uuid);

        $ret = $this->addFilter($qb, $filter, $orderBy, $pageNumber, $pageSize)
            ->setMaxResults($pageSize)
            ->setFirstResult($pageNumber * $pageSize)
            ->getQuery()
            ->getResult();

        return ['pages' => $pages, 'n' => $n, 'data' => $ret];
    }


    private function addFilter($qb, $filter= '', $orderBy='asc', $pageNumber= 0, $pageSize = 10) {
        if ($filter) {
            $qb
                ->andWhere($qb->expr()->orX(
                    $qb->expr()->like('p.name', ':filter'),
                    $qb->expr()->like('p.label', ':filter')
                ))
                ->setParameter('filter', '%' . $filter . '%');

        }
        $qb->orderBy('p.name', $orderBy);
        return $qb;
    }


    // /**
    //  * @return Product[] Returns an array of Product objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
