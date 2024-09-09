<?php

namespace App\Controller\Security\Authentication;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[Route('/securite', name: 'security_')]
class LoginController extends AbstractController
{
    public function __construct(
        private AuthenticationUtils $authenticationUtils
    ) {
    }

    #[Route('/post-login', name: 'post_login', methods: ['GET', 'POST'])]
    public function postLogin(): Response
    {
        $connexionSuccess = true;

        return $this->redirectToRoute('app_proposal', [
            'connexionSuccess' => $connexionSuccess,
        ]);
    }

    #[Route('/connexion', name: 'login', methods: ['GET', 'POST'])]
    public function login(): Response
    {
        $lastUsername = $this->authenticationUtils->getLastUsername();
        $error = $this->authenticationUtils->getLastAuthenticationError();
        if ($this->getUser()) {
            $connexionSuccess = true;

            return $this->redirectToRoute('app_proposal', [
                'connexionSuccess' => $connexionSuccess,
            ]);
        }

        return $this->render('pages/security/authentification/login/base.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }
}
