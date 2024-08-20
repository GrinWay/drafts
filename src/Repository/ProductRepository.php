<?php

namespace App\Repository;

use Doctrine\ORM\Query;
use App\Entity\Product\Product;
use App\Entity\Product\FurnitureProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /*
    */
    public function findAll(): array
    {

        $dql = \sprintf(
            'SELECT p
			FROM %1$s p
			',
            Product::class,
        );
        $query = $this->getEntityManager()->createQuery($dql)
        //  ->setHint(Query::HINT_REFRESH, true)
        ;

        return $query->getResult();
    }

    public function findAllExpiresAtGreaterThan(
        \DateTimeImmutable|\DateTime $date,
        ?bool $isPublic = null,
        bool $lastOnly = false,
    ): array|Product {

        //$qb = $this->createQueryBuilder('p')
        $qb = $this->getEntityManager()->createQueryBuilder()
            ->select('p')
            ->from($this->getEntityName(), 'p')
            ->where('p.expiresAt > :date')
            ->orderBy('p.id', 'DESC')
            ->setParameter('date', $date)
        ;

        if (!\is_null($isPublic)) {
            $qb->andWhere('p.isPublic = :isPublic')->setParameter('isPublic', $isPublic);
        }

        if ($lastOnly === true) {
            $qb
                ->orderBy('p.id', 'DESC')
                ->setMaxResults(1)
            ;

            return $qb->getQuery()->getOneOrNullResult();
        }

        $query = $qb->getQuery();

        return $query->execute();
    }

    public function findAllPriceGreaterThan(
        int|string $price,
    ): array|Product {

        $em             = $this->getEntityManager();
        $conn           = $em->getConnection();
        $classMetadata  = $em->getClassMetadata(Product::class);

        $databaseName   = $conn->getDatabase();
        $tableName      = $classMetadata->getTableName();

        $sql = '
			SELECT *
			FROM ' . $databaseName . '.' . $tableName . ' p
			WHERE p.price > ?
			ORDER BY p.id DESC
		';

        $resultSet = $conn->executeQuery($sql, [$price]);

        return $resultSet->fetchAllAssociative();
    }

//    /**
//     * @return Product[] Returns an array of Product objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Product
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
