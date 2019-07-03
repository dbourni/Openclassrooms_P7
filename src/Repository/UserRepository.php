<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends AbstractRepository
{
    public function search($term, $order = 'asc', $limit = 20, $offset = 1, $clientId)
    {
        $queryBuilder = $this->createQueryBuilder('a')
            ->select('a')
            ->orderBy('a.name', $order);

        if ($term) {
            $queryBuilder->where('a.name LIKE ?1 AND a.client = ?2')
                ->setParameter(1, '%'.$term.'%')
                ->setParameter(2, $clientId);
        } else {
            $queryBuilder->where('a.client = ?1')
                ->setParameter(1, $clientId);
        }

        return $this->paginate($queryBuilder, $limit, $offset);
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
