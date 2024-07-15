<?php

namespace GrinWay\GenericParts\Pass;

use Symfony\Component\DependencyInjection\ContainerBuilder;

class GSSetAvailableEnvsForEmailErrorLogger extends AbstractGSSetAvailableEnvs
{
    protected function doDisable(ContainerBuilder $container): void
    {
        $container->setAlias(
            MonologLoggerPass::EMAIL_ERROR_HANDLER_ID,
            'monolog.handler.null_internal',
        );
    }
}
