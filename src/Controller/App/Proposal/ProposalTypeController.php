<?php

namespace App\Controller\App\Proposal;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/', name: 'app_proposal_')]
class ProposalTypeController extends AbstractProposalController
{
    #[Route('/filtre/expressions/{type}', name: 'filter_expressions', methods: ['GET', 'POST'])]
    public function filterExpression(
        string $type,
        Request $request
    ): Response {
        $user = $this->getUser();

        if ('favoris' === $type && !$user) {
            return $this->redirectToRoute('app_proposal', [
                'favorisNotConnected' => true,
            ]);
        }

        return $this->render('pages/app/proposal/base.html.twig', $this->prepareDataForRender($request, $type));
    }
}
