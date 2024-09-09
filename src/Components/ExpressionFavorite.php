<?php

namespace App\Components;

use App\Entity\Expression;
use App\Entity\UserExpression;
use App\Repository\UserExpressionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent()]
class ExpressionFavorite extends AbstractController
{
    use DefaultActionTrait;

    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserExpressionRepository $userExpressionRepository,
    ) {
    }

    #[LiveProp]
    public ?UserExpression $userExpression;

    #[LiveProp]
    public Expression $expression;

    #[LiveProp()]
    public ?int $expressionId = null;

    #[LiveProp]
    public ?bool $isNotConnected = false;

    #[LiveProp(writable: true)]
    public ?bool $isFavorite = false;

    #[LiveAction]
    public function setFavorite(): void
    {
        $user = $this->getUser();
        $expression = $this->entityManager->getRepository(Expression::class)->find($this->expressionId);

        if (!$user) {
            $this->isNotConnected = true;

            return;
        }

        $userExpression = $this->entityManager->getRepository(UserExpression::class)->findOneBy([
            'reader' => $user,
            'expression' => $expression,
        ]);

        if (!$userExpression) {
            $userExpression = new UserExpression();
            $userExpression->setReader($user);
            $userExpression->setExpression($expression);
            $userExpression->setIsFavorite(true);
        } else {
            $userExpression->setIsFavorite(!$userExpression->getIsFavorite());
        }

        $this->isFavorite = $userExpression->getIsFavorite();

        $this->entityManager->persist($userExpression);
        $this->entityManager->flush();
    }
}
