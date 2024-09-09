<?php

namespace App\Controller\App\Proposal;

use App\Entity\Comment;
use App\Entity\User;
use App\Provider\CommentProvider;
use App\Repository\UserExpressionRepository;
use App\Service\Filter\ExpressionFilterService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractProposalController extends AbstractController
{
    public function __construct(
        protected ExpressionFilterService $expressionFilterService,
        protected CommentProvider $commentProvider,
        protected UserExpressionRepository $userExpressionRepository,
    ) {
    }

    protected function prepareDataForRender(
        Request $request,
        string $type = null
    ): array {
        $user = $this->getUser();
        $userId = null;
        if ($user instanceof User) {
            $userId = $user->getId();
        }

        $expressions = $type ? $this->expressionFilterService->displayExpressionsByType($userId, $type, $request) : $this->expressionFilterService->displayValidExpressions($request);

        $commentsByExpression = $this->commentProvider->getCommentsByExpression($expressions);

        if ($user) {
            $userExpression = $this->userExpressionRepository->findOneBy([
                'reader' => $user,
            ]);
            $favorites = array_fill_keys(array_column($this->userExpressionRepository->getFavoritesForUser($user), 'expressionId'), true);
        } else {
            $userExpression = null;
            $favorites = [];
        }

        return [
            'expressions' => $expressions,
            'commentsByExpression' => $commentsByExpression,
            'comment' => new Comment(),
            'userExpression' => $userExpression,
            'favorites' => $favorites,
        ];
    }
}
