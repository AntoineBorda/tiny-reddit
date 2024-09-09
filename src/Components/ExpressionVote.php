<?php

namespace App\Components;

use App\Entity\Expression;
use App\Repository\ExpressionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent()]
class ExpressionVote extends AbstractController
{
    use DefaultActionTrait;

    public function __construct(
        private EntityManagerInterface $entityManager,
        private ExpressionRepository $expressionRepository,
        private RequestStack $requestStack
    ) {
    }

    #[LiveProp]
    public Expression $expression;

    #[LiveProp]
    public ?int $expressionId = null;

    #[LiveProp]
    public ?bool $voteMessageSuccess = false;

    #[LiveProp]
    public ?bool $voteMessageDanger = false;

    #[LiveAction]
    public function upvote(): void
    {
        $voteKey = 'expression_'.$this->expressionId.'_vote';

        $request = $this->requestStack->getCurrentRequest();

        $request->attributes->set('expression_id', $this->expressionId);

        if (!$request->cookies->get($voteKey)) {
            $expression = $this->entityManager->getRepository(Expression::class)->find($this->expressionId);
            $expression->setUpvote($expression->getUpvote() + 1);
            $this->entityManager->flush();

            $this->voteMessageSuccess = true;
        } else {
            $this->voteMessageDanger = true;
        }
    }

    #[LiveAction]
    public function downvote(): void
    {
        $voteKey = 'expression_'.$this->expressionId.'_vote';

        $request = $this->requestStack->getCurrentRequest();

        $request->attributes->set('expression_id', $this->expressionId);

        if (!$request->cookies->get($voteKey)) {
            $expression = $this->entityManager->getRepository(Expression::class)->find($this->expressionId);
            $expression->setDownvote($expression->getDownvote() + 1);
            $this->entityManager->flush();

            $this->voteMessageSuccess = true;
        } else {
            $this->voteMessageDanger = true;
        }
    }
}
