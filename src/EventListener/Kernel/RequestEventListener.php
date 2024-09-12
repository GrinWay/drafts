<?php

namespace App\EventListener\Kernel;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Contract\EventListener\KernelEventListenerInterface;

class RequestEventListener implements KernelEventListenerInterface
{
    public function __invoke(
        RequestEvent $event,
    ): void {

        //$event->getRequest()->server->set('REMOTE_USER', 's');

        if (!$event->isMainRequest()) {
            $classMethod = \explode('::', $event->getRequest()->attributes->get('_controller'));
            $class = $classMethod[0];

            if (\count($classMethod) < 2) {
                $method = '__invoke';
            } else {
                $method = $classMethod[1];
            }

            try {
                $shortName = \sprintf(
                    '%s::%s',
                    (new \ReflectionClass($class))->getShortName(),
                    (new \ReflectionMethod($class . '::' . $method))->getName(),
                );
            } catch (\Exception $e) {
                $shortName = $class . '::' . $method;
            }

            \dump(\sprintf(
                'SUB REQUEST: "%s"',
                $shortName,
            ));
        }

        return;

        $isMainRequest = $event->isMainRequest();
        $request = $event->getRequest();
        $requestType = $event->getRequestType();
        $kernel = $event->getKernel();

        \dump($isMainRequest, $requestType, get_debug_type($kernel), get_debug_type($request));
    }
}
