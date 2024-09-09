<?php

namespace App\Controller\App\Proposal;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProposalController extends AbstractProposalController
{
    #[Route('/{_locale}', name: 'app_proposal', requirements: ['_locale' => 'en|fr'], defaults: ['_locale' => 'fr'])]
    public function index(
        Request $request
    ): Response {
        return $this->render(
            'pages/app/proposal/base.html.twig',
            $this->prepareDataForRender($request)
        );
    }
}
