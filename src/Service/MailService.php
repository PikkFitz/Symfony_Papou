<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class MailService
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendEmail(array $emailData): void
    {
        $email = (new TemplatedEmail())
            ->from($emailData['from'])
            ->to($emailData['to'])
            ->subject($emailData['subject'])
            ->htmlTemplate($emailData['htmlTemplate'])
            ->context($emailData['context']);

        $this->mailer->send($email);
    }
}
