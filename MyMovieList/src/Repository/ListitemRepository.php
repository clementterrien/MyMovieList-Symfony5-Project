<?php

namespace App\Repository;

use App\Entity\Listitem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Listitem|null find($id, $lockMode = null, $lockVersion = null)
 * @method Listitem|null findOneBy(array $criteria, array $orderBy = null)
 * @method Listitem[]    findAll()
 * @method Listitem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ListitemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Listitem::class);
    }

    // /**
    //  * @return Listitem[] Returns an array of Listitem objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Listitem
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
