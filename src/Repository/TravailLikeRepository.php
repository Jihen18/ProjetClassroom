<?php

namespace App\Repository;

use App\Entity\TravailLike;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TravailLike|null find($id, $lockMode = null, $lockVersion = null)
 * @method TravailLike|null findOneBy(array $criteria, array $orderBy = null)
 * @method TravailLike[]    findAll()
 * @method TravailLike[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TravailLikeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TravailLike::class);
    }

    // /**
    //  * @return TravailLike[] Returns an array of TravailLike objects
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
    public function findOneBySomeField($value): ?TravailLike
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
