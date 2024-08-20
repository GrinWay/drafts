<?php

namespace GrinWay\GenericParts\Pass;

use Symfony\Component\DependencyInjection\ContainerBuilder;

class GSSetAvailableEnvsForDebugLogger extends AbstractGSSetAvailableEnvs
{
    protected function doDisable(ContainerBuilder $container): void
    {
        $container->setAlias(
            'monolog.handler.grin_way_generic_parts.debug',
            'monolog.handler.null_internal',
        );
    }
}
