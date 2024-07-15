<?php

namespace App\EventListener\Kernel;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ControllerArgumentsEvent;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Contract\EventListener\KernelEventListenerInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Routing\Attribute\Route;
use Carbon\Carbon;
use Carbon\CarbonImmutable;

class ControllerArgumentsEventListener implements KernelEventListenerInterface
{
    public function __invoke(
        ControllerArgumentsEvent $event,
    ): void {
        return;
        $pa = PropertyAccess::createPropertyAccessor();

        $arguments = $event->getArguments();
        $namedArguments = $event->getNamedArguments();
        $attributes = $event->getAttributes();
        //\dd();

        $namedArguments['now'] = $namedArguments['now']->add(4, 'days');
        $event->setArguments(\array_values($namedArguments));

        //\dd($namedArguments);

        return;


        return;

        $isMainRequest = $event->isMainRequest();
        $request = $event->tRequest();
        $requestType = $event->getRequestType();
        $kernel = $event->getKernel();

        \dump($isMainRequest, $requestType, get_debug_type($kernel), get_debug_type($request));
    }
}
