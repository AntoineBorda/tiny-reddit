<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class CookieSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::RESPONSE => 'onKernelResponse',
        ];
    }

    public function onKernelResponse(ResponseEvent $event): void
    {
        $response = $event->getResponse();
        $request = $event->getRequest();

        $voteKey = 'expression_'.$request->attributes->get('expression_id').'_vote';

        if (!$request->cookies->get($voteKey)) {
            $cookie = Cookie::create($voteKey, 'true', time() + (30 * 24 * 60 * 60)); // expire dans 30 jours
            $response->headers->setCookie($cookie);
        }
    }
}
