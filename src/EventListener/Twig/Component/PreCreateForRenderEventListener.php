<?php

namespace App\EventListener\Twig\Component;

use App\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\UX\TwigComponent\Event\PreCreateForRenderEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use App\Twig\Component\Products;

#[AsEventListener]
class PreCreateForRenderEventListener
{
    public function __construct()
    {
    }

    public function __invoke(
        PreCreateForRenderEvent $event,
    ): void {
        //$isEmbeded = $event->isEmbeded();
        return;
        \dump(get_debug_type($event));
        foreach (
            [
            "getMountedComponent" => $event->getMountedComponent(),
            "getComponent" => $event->getComponent(),
            /*
            "getTemplate" => $event->getTemplate(),
            "getVariables" => $event->getVariables(),
            "getMetadata" => $event->getMetadata(),
            "getTemplateIndex" => $event->getTemplateIndex(),
            "getParentTemplateForEmbedded" => $event->getParentTemplateForEmbedded(),
            */
            ] as $name => $value
        ) {
            \dump(
                $name,
                $value,
            );
        }
        /*
        */

        return;
    }
}
