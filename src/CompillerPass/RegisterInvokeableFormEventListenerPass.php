<?php

namespace App\CompillerPass;

use function Symfony\component\string\u;

use App\Contract\EventListener\Form\InvokeableFormEventListenerInterface;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use App\Extractor\DefaultValueExtractor;
use App\Contract\EventListener\Form\FormEventListenerInterface;

class RegisterInvokeableFormEventListenerPass implements CompilerPassInterface
{
    public function __construct()
    {
    }

    public function process(ContainerBuilder $container): void
    {
        //return;
        $id = 'event_dispatcher';
        if (!$container->has($id)) {
            return;
        }
        $eventDispatcherDefinition = $container->findDefinition('event_dispatcher');

        $eventListeners = $container->findTaggedServiceIds('kernel.event_listener');
        foreach ($eventListeners as $eventClassName => $tagAttributes) {
            if (\is_subclass_of($eventClassName, InvokeableFormEventListenerInterface::class)) {
                foreach ($tagAttributes as $attributes) {
                    $event = $attributes['event'];
                    $priority = isset($attributes['priority']) ? $attributes['priority'] : 0;
                    //$method = isset($attributes['method']) ? $attributes['method'] : '__invoke';
                    $listener = new Reference($eventClassName);
                    self::addListener(
                        eventDispatcherDefinition: $eventDispatcherDefinition,
                        event: $event,
                        listener: $listener,
                        priority: $priority,
                    );
                }
                continue;
            }
        }
    }

    private static function addListener(Definition $eventDispatcherDefinition, $event, $listener, $priority): void
    {
        $arguments = [
            '$eventName' => $event,
            '$listener' => $listener,
            '$priority' => $priority,
        ];
        $eventDispatcherDefinition->addMethodCall('addListener', $arguments);
    }
}
