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

    /**
     * Find users by name, email, or both
     *
     * @param string|null $name
     * @param string|null $email
     * @param int $limit
     * @return GorestUser[]
     */
    public function findByNameOrEmail(?string $name, ?string $email, int $limit = 10): array
    {
        $qb = $this->createQueryBuilder('u');
        file_put_contents('aaa.log', $name.$email);

        if ($name) {
            $qb->andWhere('u.name LIKE :name')
               ->setParameter('name', "%{$name}%");
        }

        if ($email) {
            $qb->andWhere('u.email LIKE :email')
               ->setParameter('email', "%{$email}%");
        }

        return $qb->setMaxResults($limit)
                  ->orderBy('u.id', 'DESC')
                  ->getQuery()
                  ->getResult();
    }
}
