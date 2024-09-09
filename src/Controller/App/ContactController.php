<?php

namespace App\Controller\App;

use App\Form\ContactType;
use App\Service\Mailer\MailerContactService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    public function __construct(
        private MailerContactService $mailerContactService
    ) {
    }

    #[Route('/contact', name: 'app_contact')]
    public function contact(
        Request $request,
    ): Response {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);
        $name = $form->get('name')->getData();
        $email = $form->get('email')->getData();
        $message = $form->get('message')->getData();
        if ($form->isSubmitted() && $form->isValid()) {
            $sendMessageSuccess = true;
            $this->mailerContactService->sendContactEmail($name, $email, $message);

            return $this->redirectToRoute('app_contact', [
                'sendMessageSuccess' => $sendMessageSuccess,
            ]);
        }

        return $this->render('pages/app/contact/base.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
