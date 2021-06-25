<?php

namespace App\Repository;

use App\Entity\CartProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CartProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method CartProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method CartProduct[]    findAll()
 * @method CartProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CartProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CartProduct::class);
    }

    public function findByCart($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.cart = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findTotalPrice($value)
    {
        return $this->createQueryBuilder('c')
        ->innerJoin('c.product', 'p')
        ->andWhere('c.cart = :val')
        ->setParameter('val', $value)
        ->select('SUM(c.quantity * p.price) as total')
        ->getQuery()
        ->getOneOrNullResult();

    }

    /**
     * Delete dans le repo ????
     */
    public function deleteCart($value)
    {
        return $this->createQueryBuilder('c')
        ->delete()
        ->where('c.cart = :val')
        ->setParameter('val', $value)
        ->getQuery()
        ->getResult();

    }

    // /**
    //  * @return CartProduct[] Returns an array of CardProduct objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CardProduct
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
