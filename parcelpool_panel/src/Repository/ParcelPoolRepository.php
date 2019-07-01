<?php

namespace App\Repository;

use App\Entity\ParcelPool;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ParcelPool|null find($id, $lockMode = null, $lockVersion = null)
 * @method ParcelPool|null findOneBy(array $criteria, array $orderBy = null)
 * @method ParcelPool[]    findAll()
 * @method ParcelPool[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParcelPoolRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ParcelPool::class);
    }

    // /**
    //  * @return ParcelPool[] Returns an array of ParcelPool objects
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
    public function findOneBySomeField($value): ?ParcelPool
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
