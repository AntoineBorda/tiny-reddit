<?php

namespace App\Controller\Account\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
#[Route('/utilisateur', name: 'user_')]
class AccountController extends AbstractController
{
    #[Route('/compte', name: 'account')]
    public function base(): Response
    {
        return $this->render('pages/account/user/account/base.html.twig', []);
    }
}
