<?php

namespace App\CompillerPass;

use App\Messenger\MessengerManager;
use Symfony\Component\DependencyInjection\Reference;

use function Symfony\component\string\u;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use App\Extractor\DefaultValueExtractor;

class MessengerManagerPass implements CompilerPassInterface
{
    public function __construct()
    {
    }

    public function process(ContainerBuilder $container): void
    {
        if (!$container->has(MessengerManager::class)) {
            return;
        }

        $messengerManager = $container->findDefinition(MessengerManager::class);

        $arguments = [
            '$commandBusHandlers' => [],
            '$eventBusHandlers' => [],
        ];

        $commandBusIds = $container->findTaggedServiceIds('app.command_bus_handler');
        $eventBusIds = $container->findTaggedServiceIds('app.event_bus_handler');

        foreach ($commandBusIds as $id => $dopTags) {
            if (!$container->has($id)) {
                continue;
            }
            $arguments['$commandBusHandlers'][] = $container->findDefinition($id);
        }
        foreach ($eventBusIds as $id => $dopTags) {
            if (!$container->has($id)) {
                continue;
            }
            $arguments['$eventBusHandlers'][] = $container->findDefinition($id);
        }
        $messengerManager->setArguments($arguments);
    }
}
