<?php

namespace App\Repository;

use App\Entity\PreparationDateManger;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PreparationDateManger|null find($id, $lockMode = null, $lockVersion = null)
 * @method PreparationDateManger|null findOneBy(array $criteria, array $orderBy = null)
 * @method PreparationDateManger[]    findAll()
 * @method PreparationDateManger[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PreparationDateMangerRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PreparationDateManger::class);
    }

    // /**
    //  * @return PreparationDateManger[] Returns an array of PreparationDateManger objects
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
    public function findOneBySomeField($value): ?PreparationDateManger
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
