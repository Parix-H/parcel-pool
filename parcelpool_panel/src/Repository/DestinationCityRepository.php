<?php

namespace App\Repository;

use App\Entity\DestinationCity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method DestinationCity|null find($id, $lockMode = null, $lockVersion = null)
 * @method DestinationCity|null findOneBy(array $criteria, array $orderBy = null)
 * @method DestinationCity[]    findAll()
 * @method DestinationCity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DestinationCityRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DestinationCity::class);
    }

    // /**
    //  * @return DestinationCity[] Returns an array of DestinationCity objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DestinationCity
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
