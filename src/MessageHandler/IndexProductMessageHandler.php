<?php

namespace App\MessageHandler;

use App\Application\Actions\Product\IndexProductAction;
use App\Message\IndexProductMessage;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class IndexProductMessageHandler
    implements MessageHandlerInterface
{
    private IndexProductAction $indexProductAction;

    private LoggerInterface $logger;

    public function __construct(
        IndexProductAction $indexProductAction,
        LoggerInterface $logger
    ) {
        $this->indexProductAction = $indexProductAction;
        $this->logger = $logger;
    }

    public function __invoke(IndexProductMessage $message)
    {
        try {
            $this->indexProductAction->execute($message->getProductId());
        } catch (\Throwable $e) {
            $this->logger->error($e->getTraceAsString());
        }
    }
}
