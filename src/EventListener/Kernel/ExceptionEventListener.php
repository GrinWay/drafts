<?php

namespace App\EventListener\Kernel;

use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Contract\EventListener\KernelEventListenerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ExceptionEventListener implements KernelEventListenerInterface
{
    public function __construct(
        //private readonly RequestStack $requestStack,
    ) {
    }

    public function __invoke(
        ExceptionEvent $event,
    ): void {
        return;
        //\dd('LISTENER');
        //if (!$event instanceof ExceptionEvent) return;

        $throwable = $event->getThrowable();
        $code = $throwable->getCode();
        $message = $throwable->getMessage();
        $message = \sprintf(
            'There was an Exception "%s" with the code "%s"',
            $message,
            $code,
        );

        $r = new Response();
        $r->setContent($message);

        $event->allowCustomResponseCode();
        $r->setStatusCode(551);

        $event->setResponse($r);
    }
}
