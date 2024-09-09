<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\UserExpression;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserExpression>
 *
 * @method UserExpression|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserExpression|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserExpression[]    findAll()
 * @method UserExpression[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserExpressionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserExpression::class);
    }

    public function getFavoritesForUser(User $user)
    {
        return $this->createQueryBuilder('ue')
            ->select('IDENTITY(ue.expression) as expressionId')
            ->where('ue.reader = :user')
            ->andWhere('ue.isFavorite = true')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }
}
