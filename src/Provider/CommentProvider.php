<?php

namespace App\Provider;

use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;

class CommentProvider
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function getCommentsByExpression($expressions): array
    {
        $commentsByExpression = [];
        foreach ($expressions as $expression) {
            $expressionId = $expression->getId();
            $commentsByExpression[$expressionId] = $this->entityManager
                ->getRepository(Comment::class)
                ->findBy(['expression' => $expressionId], ['createdAt' => 'DESC']);
        }

        return $commentsByExpression;
    }
}
