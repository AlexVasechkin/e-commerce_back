<?php

namespace App\MessageHandler;

use App\Application\Actions\Email\DTO\SendTextEmailRequest;
use App\Application\Actions\Email\SendTextEmailAction;
use App\Message\SendEmailMessage;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class SendEmailMessageHandler
    implements MessageHandlerInterface
{
    private SendTextEmailAction $sendTextEmailAction;

    private LoggerInterface $logger;

    public function __construct(
        SendTextEmailAction $sendTextEmailAction,
        LoggerInterface $logger
    ) {
        $this->sendTextEmailAction = $sendTextEmailAction;
        $this->logger = $logger;
    }

    public function __invoke(SendEmailMessage $message)
    {
        try {
            $this->sendTextEmailAction->execute(
                new SendTextEmailRequest(
                    $message->getTo(),
                    $message->getSubject(),
                    $message->getTextContent(),
                    $message->getHtmlContent()
                )
            );
        } catch (\Throwable $e) {
            $this->logger->error(implode(PHP_EOL, [
                $e->getMessage(),
                $e->getTraceAsString()
            ]));
        }
    }
}
