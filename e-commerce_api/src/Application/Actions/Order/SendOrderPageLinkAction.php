<?php

namespace App\Application\Actions\Order;

use App\Message\SendEmailMessage;
use App\Repository\OrderRepository;
use Symfony\Component\Messenger\MessageBusInterface;

class SendOrderPageLinkAction
{
    private MessageBusInterface $messageBus;

    public function __construct(
        MessageBusInterface $messageBus
    ) {
        $this->messageBus = $messageBus;
    }

    public function execute(string $to, string $orderId): void
    {
        $this->messageBus->dispatch(
            new SendEmailMessage(
                $to,
                'Новый заказ',
                'Оформлен новый заказ',
                implode(PHP_EOL, [
                    '<p>Оформлен новый заказ.</p>',
                    sprintf('<p>Состав и стоимость заказа доступны по <a href="%s">ссылке</a></p>', $orderId)
                ])
            )
        );
    }
}
