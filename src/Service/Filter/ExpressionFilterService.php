<?php

namespace App\Service\Filter;

use App\Repository\ExpressionRepository;
use App\Service\Pagination\PaginationService;
use Symfony\Component\HttpFoundation\Request;

class ExpressionFilterService
{
    public function __construct(
        private ExpressionRepository $expressionRepository,
        private PaginationService $paginationService
    ) {
    }

    public function displayValidExpressions(
        Request $request
    ): object {
        $query = $this->expressionRepository->findValidExpressions();

        return $this->paginationService->createPagination($query, $request);
    }

    public function displayExpressionsByType(
        ?int $userId,
        string $type,
        Request $request
    ): object {
        $query = match ($type) {
            'recentes' => $this->expressionRepository->findNewerExpressions(),
            'anciennes' => $this->expressionRepository->findOlderExpressions(),
            'favoris' => $this->expressionRepository->findFavoriteExpressionsForUser($userId),
            'best-notes' => $this->expressionRepository->findBestRatingExpressions(),
            'worst-notes' => $this->expressionRepository->findWorstRatingExpressions(),
            default => throw new \InvalidArgumentException("Invalid filter type: $type"),
        };

        return $this->paginationService->createPagination($query, $request);
    }
}
