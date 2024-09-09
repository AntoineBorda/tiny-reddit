<?php

namespace App\Controller\Security\Authentication;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\Security\Http\Authenticator\FormLoginAuthenticator;

#[Route('/securite', name: 'security_')]
class RegistrationController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private FormLoginAuthenticator $authenticator,
        private UserAuthenticatorInterface $userAuthenticator,
    ) {
    }

    #[Route('/inscription', name: 'registration', methods: ['GET', 'POST'])]
    public function registration(
        Request $request
    ): Response {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user, [
            'validation_groups' => ['registration'],
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            $registrationSuccess = true;
            $userAuthenticator = $this->userAuthenticator;
            $userAuthenticator->authenticateUser($user, $this->authenticator, $request);

            return $this->redirectToRoute('app_proposal', [
                'registrationSuccess' => $registrationSuccess,
            ]);
        }

        return $this->render('pages/security/authentification/registration/base.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
