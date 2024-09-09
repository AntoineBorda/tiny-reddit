<?php

namespace App\Controller\Account\Admin;

use App\Provider\PublisherProvider;
use App\Repository\ExpressionRepository;
use App\Service\Mailer\MailerExpressionStateService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_EVOCATI')]
#[Route('/admin', name: 'admin_')]
class RequestController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private MailerExpressionStateService $mailerExpressionStateService,
        private PublisherProvider $publisherProvider,
    ) {
    }

    #[IsGranted('ROLE_EVOCATI')]
    #[Route('/requete', name: 'request')]
    public function showRequestExpression(
        ExpressionRepository $expressionRepository
    ): Response {
        $expressions = $expressionRepository->findValidAndNotInvalidExpressions();

        return $this->render('pages/account/admin/request_expression/base.html.twig', [
            'expressions' => $expressions,
        ]);
    }

    #[IsGranted('ROLE_EVOCATI')]
    #[Route('/requete/valider/{id}', name: 'request_validate')]
    public function validateRequestExpression(
        ExpressionRepository $expressionRepository,
        int $id
    ): Response {
        $expression = $expressionRepository->find($id);
        $requestValidateSuccess = false;

        if ($expression) {
            $publisherPseudo = $expression->getPublisher();
            $publisherEmail = $this->publisherProvider->getPublisherEmailByPseudo($publisherPseudo);
            $expression->setIsValidate(true);
            $this->entityManager->flush();
            $requestValidateSuccess = true;
            $this->mailerExpressionStateService->sendStateValidateEmail($publisherEmail);
        }

        return $this->redirectToRoute('admin_request', [
            'requestValidateSuccess' => $requestValidateSuccess,
        ]);
    }

    #[IsGranted('ROLE_EVOCATI')]
    #[Route('/requete/invalider/{id}', name: 'request_invalidate')]
    public function invalidateRequestExpression(
        ExpressionRepository $expressionRepository,
        int $id
    ): Response {
        $expression = $expressionRepository->find($id);
        $requestInvalidateSuccess = false;

        if ($expression) {
            $publisherPseudo = $expression->getPublisher();
            $publisherEmail = $this->publisherProvider->getPublisherEmailByPseudo($publisherPseudo);
            $expression->setIsInvalidate(true);
            $this->entityManager->flush();
            $requestInvalidateSuccess = true;
            $this->mailerExpressionStateService->sendStateInvalidateEmail($publisherEmail);
        }

        return $this->redirectToRoute('admin_request', [
            'requestInvalidateSuccess' => $requestInvalidateSuccess,
        ]);
    }
}
