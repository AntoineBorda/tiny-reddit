<?php

namespace App\Controller\App;

use App\Entity\Expression;
use App\Form\ExpressionType;
use App\Service\Mailer\MailerExpressionValidationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/ajouter-une-proposition', name: 'app_addexpression')]
class AddExpressionController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private SluggerInterface $slugger,
        private MailerExpressionValidationService $mailerExpressionValidationService
    ) {
    }

    #[Route('/', name: '', methods: ['GET', 'POST'])]
    public function addExpression(
        Request $request
    ): Response {
        $expression = new Expression();

        $user = $this->getUser();

        $form = $this->createForm(ExpressionType::class, $expression);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->getUser()) {
                $expression->setPublisher($user);
                $expression->setIsValidate(false);
                $expression->setIsInvalidate(false);
                $expression->setSlug($this->slugger->slug($expression->getExpression()));

                $this->entityManager->persist($expression);
                $this->entityManager->flush();

                $addExpressionSuccess = true;
                $this->mailerExpressionValidationService->sendValidationEmail();

                return $this->redirectToRoute('app_addexpression', [
                    'addExpressionSuccess' => $addExpressionSuccess,
                ]);
            } else {
                $addExpressionDanger = true;

                return $this->redirectToRoute('app_addexpression', [
                    'addExpressionDanger' => $addExpressionDanger,
                ]);
            }
        }

        return $this->render('pages/app/addexpression/base.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
