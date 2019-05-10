<?php

namespace App\Repository;

use App\Entity\EtapeRecette;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method EtapeRecette|null find($id, $lockMode = null, $lockVersion = null)
 * @method EtapeRecette|null findOneBy(array $criteria, array $orderBy = null)
 * @method EtapeRecette[]    findAll()
 * @method EtapeRecette[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EtapeRecetteRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, EtapeRecette::class);
    }

    // /**
    //  * @return EtapeRecette[] Returns an array of EtapeRecette objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EtapeRecette
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
