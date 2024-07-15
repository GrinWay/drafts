<?php

namespace App\Extension\Test;

use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\Definition\Processor;

class ExtensionExample extends Extension
{
    public function getAlias(): string
    {
        return 'extension_example';
    }

    public function load(array $configs, ContainerBuilder $container): void
    {

        //\dump(__METHOD__);
        /*
        $configs = \array_pop($configs);
        $configuration = new Configuration;
        $processor = new Processor();
        $config = $processor->processConfiguration($configuration, $configs);
        \dd($configs);
        */

        /*
        \dd(
            $configs,
        );
        */
    }
}
