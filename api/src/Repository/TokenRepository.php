<?php

namespace App\Repository;

use App\Entity\Token;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Token|null find($id, $lockMode = null, $lockVersion = null)
 * @method Token|null findOneBy(array $criteria, array $orderBy = null)
 * @method Token[]    findAll()
 * @method Token[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TokenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Token::class);
    }


    public function getValidTokenForUser(User $user) {
        $nowTs = (new \DateTimeImmutable())->getTimestamp();
        $res = $this->createQueryBuilder('t')
            ->join('t.user', 'u')
            ->where('u.email = :email')
            ->andWhere('t.expire > :now')
            ->orderBy('t.expire', 'asc')
            ->setParameter('email', $user->getEmail())
            ->setParameter('now', $nowTs)
            ->setMaxResults(1)
            ->getQuery()
            ->getResult()
        ;
        return count($res)>0 ? $res[0] : null;
    }

    public function getTokensForUser(User $user) {
        return $this->createQueryBuilder('t')
            ->join('t.user', 'u')
            ->where('u.email = :email')
            ->setParameter('email', $user->getEmail())
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Token[] Returns an array of Token objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Token
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
