<?php

namespace App\Controller\Security\Authentication;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/securite', name: 'security_')]
class LogoutController extends AbstractController
{
    public function __construct()
    {
    }

    #[Route('/post-logout', name: 'post_logout', methods: ['GET', 'POST'])]
    public function postLogout(): Response
    {
        $logoutSuccess = true;

        return $this->redirectToRoute('app_proposal', [
            'logoutSuccess' => $logoutSuccess,
        ]);
    }

    #[Route('/deconnexion', name: 'logout')]
    public function logout(): void
    {
    }
}
