<?php

namespace App\EventListener\Kernel;

use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpFoundation\RequestStack;

class ResponseEventListener
{
    public function __construct()
    {
    }

    public function __invoke(
        ResponseEvent $event,
    ): void {
        return;

        $request = $event->getRequest();
        $response = $event->getResponse();

        if ($token = $request->attributes->get('_auth_token')) {
            $response->headers->set('APP-AUTH-TOKEN', $token);
            //\dump('_auth_token: ' . $token);
        }
    }
}
