<?php

namespace App\EventListener\Kernel;

use App\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Contract\EventListener\KernelEventListenerInterface;

class ControllerEventListener implements KernelEventListenerInterface
{
    public function __construct()
    {
    }

    public function __invoke(
        ControllerEvent $event,
    ): void {
        return;

        $controller = $event->getController();
        $attributes = $event->getAttributes();
		\dd($attributes);
        $reflector = $event->getControllerReflector();
        $request = $event->getRequest();

        if (\is_array($controller)) {
            $controller = \array_shift($controller);
        }

        if ($controller instanceof AbstractController) {
            $request->attributes->set('_auth_token', md5(rand(0, 1_000)));
        }


        //\dd($attributes);
    }
}
