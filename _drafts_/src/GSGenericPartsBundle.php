<?php

namespace GrinWay\GenericParts;

use Symfony\Component\EventDispatcher\DependencyInjection\AddEventAliasesPass;
use GrinWay\GenericParts\Pass\{
    AddEventAliasPass,
    MonologLoggerPass
};
use GrinWay\GenericParts\GSGenericPartsExtension;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\DependencyInjection\Compiler\ResolveEnvPlaceholdersPass;

class GrinWayGenericPartsBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }

    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container
            //->addCompilerPass(new ResolveEnvPlaceholdersPass)
            ->addCompilerPass(new AddEventAliasPass([]))
            ->addCompilerPass(new MonologLoggerPass())
        ;
    }

    public function getContainerExtension(): ?ExtensionInterface
    {
        if ($this->extension === null) {
            $this->extension = new GSGenericPartsExtension();
        }

        return $this->extension;
    }
}
