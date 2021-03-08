<?php

namespace App\Repository;

use App\Entity\Movielist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Movielist|null find($id, $lockMode = null, $lockVersion = null)
 * @method Movielist|null findOneBy(array $criteria, array $orderBy = null)
 * @method Movielist[]    findAll()
 * @method Movielist[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovielistRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Movielist::class);
    }

    // /**
    //  * @return Movielist[] Returns an array of Movielist objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Movielist
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
