<?php

namespace App\Repository;

use App\Entity\Winning;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Winning|null find($id, $lockMode = null, $lockVersion = null)
 * @method Winning|null findOneBy(array $criteria, array $orderBy = null)
 * @method Winning[]    findAll()
 * @method Winning[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WinningRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Winning::class);
    }

    public function getSumMoneyByUserId($userId)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.id_user = :id')
            ->setParameter('id', $userId)
            ->select('SUM(b.money) as moneySum')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult()
            ;
    }

    // /**
    //  * @return Winnings[] Returns an array of Winnings objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Winnings
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
