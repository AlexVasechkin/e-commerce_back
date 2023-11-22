<?php

namespace App\Application\Actions\Email;

use App\Application\Actions\Email\DTO\SendTextEmailRequest;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class SendTextEmailAction
{
    private ParameterBagInterface $parameterBag;

    private MailerInterface $mailer;

    public function __construct(
        ParameterBagInterface $parameterBag,
        MailerInterface $mailer
    ) {
        $this->parameterBag = $parameterBag;
        $this->mailer = $mailer;
    }

    public function execute(SendTextEmailRequest $request): void
    {
        $email = (new Email())
            ->from($this->parameterBag->get('app.email.from'))
            ->to($request->getTo())
            ->subject($request->getSubject())
            ->text($request->getTextContent())
            ->html($request->getHtmlContent())
        ;

        $this->mailer->send($email);
    }
}
