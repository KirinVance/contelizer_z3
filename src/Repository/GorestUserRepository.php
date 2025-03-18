<?php

namespace App\Repository;

use App\Entity\GorestUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<GorestUser>
 *
 * @method GorestUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method GorestUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method GorestUser[]    findAll()
 * @method GorestUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GorestUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GorestUser::class);
    }

//    /**
//     * @return GorestUser[] Returns an array of GorestUser objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('g.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?GorestUser
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
