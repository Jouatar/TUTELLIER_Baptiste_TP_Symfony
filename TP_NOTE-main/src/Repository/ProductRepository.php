<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function orderByDate(): array
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = '
            SELECT * FROM `product` ORDER by created_at DESC
            ';
        $stmt = $conn->prepare($sql);
        $result = $stmt->executeQuery();

        // returns an array of arrays (i.e. a raw data set)
        return $result->fetchAllAssociative();
    }

    public function orderByPrice(): array
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = '
            SELECT * FROM `product` ORDER by price
            ';
            $stmt = $conn->prepare($sql);
            $result = $stmt->executeQuery();
    
            // returns an array of arrays (i.e. a raw data set)
            return $result->fetchAllAssociative();
    }

    // /**
    //  * @return Product[] Returns an array of Product objects
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
    public function findOneBySomeField($value): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    //SELECT * FROM `product` ORDER by created_at DESC;

    //SELECT * FROM `product` ORDER by price DESC;
}
