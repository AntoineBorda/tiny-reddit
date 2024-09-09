<?php

namespace App\Controller\App;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GuessrController extends AbstractController
{
    #[Route('/guessr', name: 'app_guessr')]
    public function index(): Response
    {
        return $this->render('pages/app/guessr/base.html.twig', []);
    }
}
