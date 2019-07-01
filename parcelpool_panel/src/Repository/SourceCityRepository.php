<?php

namespace App\Repository;

use App\Entity\SourceCity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method SourceCity|null find($id, $lockMode = null, $lockVersion = null)
 * @method SourceCity|null findOneBy(array $criteria, array $orderBy = null)
 * @method SourceCity[]    findAll()
 * @method SourceCity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SourceCityRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SourceCity::class);
    }

    // /**
    //  * @return SourceCity[] Returns an array of SourceCity objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SourceCity
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
