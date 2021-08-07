<?php

namespace App\Repository;

use App\Entity\VariantLabel;
use App\Model\Label;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method VariantLabel|null find($id, $lockMode = null, $lockVersion = null)
 * @method VariantLabel|null findOneBy(array $criteria, array $orderBy = null)
 * @method VariantLabel[]    findAll()
 * @method VariantLabel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VariantLabelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VariantLabel::class);
    }

    public function queryVariant($like) {
        //  TODO rajouter la dépendance à Shop.
        return $this->createQueryBuilder('v')
            ->where('LOWER(v.label) LIKE :like')
            ->setParameter('like', '%'. strtolower($like) . '%')
            ->getQUery()
            ->getResult();
    }

    public function getLabelsFromVariantMapping($mapping, $logger) {
        $tab = [];
        $keys = explode('#', $mapping);
        foreach($keys as $key) {
            $tab[] = explode('_', $key);
        }
        $res = $this->createQueryBuilder('v')
            ->where('v.id IN ( :list )')
            ->leftJoin('v.variantName', 'vn')
            ->andWhere('vn.id IN (:vnids)')
            ->setParameter('list',array_map(function($elem) { return $elem[1];}, $tab))
            ->setParameter('vnids',array_map(function($elem) { return $elem[0];}, $tab))
            ->getQuery()
            ->getResult()
        ;
        $ret = [];
        $logger->error('here [tab]', $tab);
        $logger->error('here [count]' , [count($res)]);
        foreach($res as $vl) {

            $label = new Label();
            $label->setLabel($vl->getLabel());
            $label->setId($vl->getId());
            $label->setName($vl->getVariantName()[0]->getName());
            $ret[] = $label;
        }
        return $ret;
    }

    // /**
    //  * @return VariantLabel[] Returns an array of VariantLabel objects
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
    public function findOneBySomeField($value): ?VariantLabel
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
