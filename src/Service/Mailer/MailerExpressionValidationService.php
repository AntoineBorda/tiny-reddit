<?php

namespace App\Service\Mailer;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class MailerExpressionValidationService
{
    public function __construct(
        private MailerInterface $mailer,
        private Environment $twig
    ) {
    }

    public function sendValidationEmail()
    {
        $adminUrl = 'https://www.starcitizengame.gg/admin/requete';
        $websiteUrl = 'https://www.starcitizengame.gg/';
        $htmlContent = $this->twig->render('emails/mailer_expression_validation.html.twig', [
            'admin_url' => $adminUrl,
            'website_url' => $websiteUrl,
        ]);

        $email = (new Email())
            ->from('dev@antoineborda.com')
            ->to('dev@antoineborda.com')
            ->subject('Nouvelle expression à valider sur StarCitizenGame.fr')
            ->html($htmlContent);

        $this->mailer->send($email);
    }
}
