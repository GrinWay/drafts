<?php

namespace GrinWay\GenericParts\Pass;

use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class AddEventAliasPass implements CompilerPassInterface
{
    public function __construct(
        private readonly array $eventAliases,
        private readonly string $eventDispatcherParameterName = 'event_dispatcher.event_aliases',
    ) {
    }

    public function process(ContainerBuilder $container)
    {
        $currentEventDispatcherParams           = $container->hasParameter($this->eventDispatcherParameterName)
                                                ? $container->getParameter($this->eventDispatcherParameterName)
                                                : []
        ;

        $container->setParameter(
            $this->eventDispatcherParameterName,
            \array_merge($this->eventAliases, $currentEventDispatcherParams),
        );
    }
}
