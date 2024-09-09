<?php

namespace App\Controller\Account\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_EVOCATI')]
#[Route('/admin', name: 'admin')]
class AdminController extends AbstractController
{
    #[Route('/', name: '')]
    public function index(): Response
    {
        return $this->render('pages/account/admin/admin_area/base.html.twig', []);
    }
}
