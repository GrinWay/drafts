<?php

namespace App\EventListener\Kernel;

use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FinishRequestEvent;
use Symfony\Component\HttpFoundation\RequestStack;

class FinishRequestEventListener
{
    public function __construct()
    {
    }

    public function __invoke(
        FinishRequestEvent $event,
    ): void {

        return;
    }
}
