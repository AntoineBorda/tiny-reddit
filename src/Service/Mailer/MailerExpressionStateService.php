<?php

namespace App\Service\Mailer;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class MailerExpressionStateService
{
    public function __construct(
        private MailerInterface $mailer,
        private Environment $twig
    ) {
    }

    public function sendStateValidateEmail(string $publisherEmail)
    {
        $websiteUrl = 'https://www.starcitizengame.gg/';
        $htmlContent = $this->twig->render('emails/mailer_expression_state_validate.html.twig', [
            'website_url' => $websiteUrl,
        ]);

        $email = (new Email())
            ->from('dev@antoineborda.com')
            ->to($publisherEmail)
            ->subject('Ta proposition à été validée !')
            ->html($htmlContent);

        $this->mailer->send($email);
    }

    public function sendStateInvalidateEmail(string $publisherEmail)
    {
        $websiteUrl = 'https://www.starcitizengame.gg/';
        $contactUrl = 'https://www.starcitizengame.gg/contact';
        $htmlContent = $this->twig->render('emails/mailer_expression_state_invalidate.html.twig', [
            'website_url' => $websiteUrl,
            'contact_url' => $contactUrl,
        ]);

        $email = (new Email())
            ->from('dev@antoineborda.com')
            ->to($publisherEmail)
            ->subject('Ta proposition à été refusée !')
            ->html($htmlContent);

        $this->mailer->send($email);
    }
}
