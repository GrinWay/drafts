<?php

namespace App\EventListener\Kernel;

use App\Attribute\Twig\MyTemplate;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Contract\EventListener\KernelEventListenerInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Routing\Attribute\Route;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use App\Response\AbstractResponse;
use Twig\Environment;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(priority: -129)]
class ViewEventListener// implements KernelEventListenerInterface
{
    public function __construct(
        private readonly Environment $twig,
    ) {
    }

    public function __invoke(
        ViewEvent $event,
    ): void {
        return;
		\dd('VIEW');
        $result = $event->getControllerResult();

        $controllerMethodCallable = $event->getRequest()->attributes->get('_controller');
        if (\is_array($controllerMethodCallable)) {
            $controllerMethodCallable = \implode('::', $controllerMethodCallable);
        }
        $controllerMethodRefl = new \ReflectionMethod($controllerMethodCallable);
        $controllerMethodAttributes = $controllerMethodRefl->getAttributes();
        $controllerMethodTemplateAttr = null;
        foreach ($controllerMethodAttributes as $methodAttribute) {
            $methodAttributeName = $methodAttribute->getName();
            if ($methodAttributeName === MyTemplate::class || \is_subclass_of($methodAttributeName, MyTemplate::class)) {
                $controllerMethodTemplateAttr = $methodAttribute;
                unset($methodAttribute);
                break;
            }
        }
        if (!\is_array($result) || null == $controllerMethodTemplateAttr) {
            return;
        }

        [
            'path' => $path,
        ] = $controllerMethodTemplateAttr->getArguments();
        $variables = $result;

        $r = $this->twig->render($path, $variables);
        $event->setResponse(new Response($this->twig->render($path, $variables)));

        return;


        \dd($event->getControllerResult());

        return;
    }

    /*
    public function getDefaultPriority(): int {
        \dd(__METHOD__);
        return 100;
    }
    */
}
