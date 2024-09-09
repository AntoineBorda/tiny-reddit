<?php

namespace App\Service\Mailer;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailerContactService
{
    public function __construct(
        private MailerInterface $mailer
    ) {
    }

    public function sendContactEmail(string $name, string $email, string $message)
    {
        $email = (new Email())
            ->from('dev@antoineborda.com')
            ->to('dev@antoineborda.com')
            ->subject('Nouveau message de '.$name)
            ->text('Message de '.$name.' ('.$email.') : '.$message)
            ->html('<p>Message de '.$name.' ('.$email.') : '.$message.'</p>');

        $this->mailer->send($email);
    }
}
