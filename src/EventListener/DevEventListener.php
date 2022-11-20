<?php

namespace App\EventListener;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class DevEventListener
{
    private ParameterBagInterface $parameterBag;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
    }

    public function onDevRequest(ResponseEvent $event)
    {
        if (!$event->isMainRequest()) {
            return;
        }

        if ($this->parameterBag->get('app.env') === 'dev') {
            $event->getResponse()->headers->set('Access-Control-Allow-Origin', '*');
        }
    }
}
