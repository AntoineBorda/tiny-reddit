<?php

namespace App\Repository;

use App\Entity\Expression;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Expression>
 *
 * @method Expression|null find($id, $lockMode = null, $lockVersion = null)
 * @method Expression|null findOneBy(array $criteria, array $orderBy = null)
 * @method Expression[]    findAll()
 * @method Expression[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExpressionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Expression::class);
    }

    // ADMIN VALIDATION REQUEST
    public function findValidAndNotInvalidExpressions()
    {
        $qb = $this->createQueryBuilder('e');

        $qb->orderBy('e.createdAt', 'DESC')
            ->where('e.is_validate = false')
            ->andWhere('e.is_invalidate = false');

        return $qb->getQuery()->getResult();
    }

    // DISPLAY EXPRESSIONS
    public function findValidExpressions()
    {
        $qb = $this->createQueryBuilder('e');

        $qb->select('e')
            ->where('e.is_validate = true')
            ->orderBy('e.upvote', 'DESC')
            ->addOrderBy('e.createdAt', 'DESC');

        return $qb->getQuery();
    }

    // FILTER BY DATE NEWER
    public function findNewerExpressions()
    {
        $qb = $this->createQueryBuilder('e');

        $qb->orderBy('e.createdAt', 'DESC')
            ->where('e.is_validate = true');

        return $qb->getQuery();
    }

    // FILTER BY DATE OLDER
    public function findOlderExpressions()
    {
        $qb = $this->createQueryBuilder('e');

        $qb->orderBy('e.createdAt', 'ASC')
            ->where('e.is_validate = true');

        return $qb->getQuery();
    }

    // FILTER BY IS FAVORITE
    public function findFavoriteExpressionsForUser($userId)
    {
        $qb = $this->createQueryBuilder('e')
            ->orderBy('e.createdAt', 'DESC')
            ->join('e.userExpressions', 'ue')
            ->where('ue.reader = :userId')
            ->andWhere('ue.isFavorite = true')
            ->andwhere('e.is_validate = true')
            ->setParameter('userId', $userId);

        return $qb->getQuery();
    }

    // FILTER BY BEST RATING
    public function findBestRatingExpressions()
    {
        return $this->createQueryBuilder('e')
            ->select('e')
            ->orderBy('e.upvote', 'DESC')
            ->getQuery();
    }

    // FILTER BY WORST RATING
    public function findWorstRatingExpressions()
    {
        return $this->createQueryBuilder('e')
            ->select('e')
            ->orderBy('e.downvote', 'DESC')  // Si tu souhaites vraiment voir les plus bas upvotes en premier
            ->getQuery();
    }
}
