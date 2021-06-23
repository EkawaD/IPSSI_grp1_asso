<?php

namespace App\Repository;

use App\Entity\Pet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Pet|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pet|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pet[]    findAll()
 * @method Pet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pet::class);
    }

    // /**
    //  * @return Pet[] Returns an array of Pet objects
    //  */
    public function findPetLastMonth()
    {        
        $qb = $this->createQueryBuilder('p');
        return $qb
            ->select('p.id', 'p.name', 'p.species', 'p.breed', 'p.age', 'p.weight', 'p.sex', 'p.adoptedAt', 'p.image')
            ->andWhere('DATE_DIFF(CURRENT_DATE(), p.adoptedAt) < 30')
            ->orderBy('p.id', 'ASC')
            // ->setMaxResults(30)
            ->getQuery()
            ->getResult()
        ;
    }



    /*
    public function findOneBySomeField($value): ?Pet
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
