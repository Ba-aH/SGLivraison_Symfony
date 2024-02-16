<?php

namespace App\Repository;

use App\Entity\TestTemplate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TestTemplate>
 *
 * @method TestTemplate|null find($id, $lockMode = null, $lockVersion = null)
 * @method TestTemplate|null findOneBy(array $criteria, array $orderBy = null)
 * @method TestTemplate[]    findAll()
 * @method TestTemplate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TestTemplateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TestTemplate::class);
    }

//    /**
//     * @return TestTemplate[] Returns an array of TestTemplate objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?TestTemplate
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
