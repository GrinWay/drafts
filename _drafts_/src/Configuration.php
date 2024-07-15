<?php

namespace GrinWay\GenericParts;

use function Symfony\Component\String\u;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function __construct(
		private readonly string $timezoneSessionName,
    ) {
    }

    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder(GSGenericPartsExtension::PREFIX);

        $treeBuilder->getRootNode()
            ->children()

				->scalarNode(GSGenericPartsExtension::LOCALE)
					->isRequired()
                    ->info('Locale for services')
                    #->defaultValue('%grin_way_generic_parts.locale%') Don't work, it's a simple string if defaultValue
                ->end()

                ->scalarNode(GSGenericPartsExtension::TIMEZONE)
					->isRequired()
                    ->info('Timezone for services')
                ->end()

                ->scalarNode(GSGenericPartsExtension::TIMEZONE_SESSION_NAME)
                    ->info('The session name')
					->defaultValue($this->timezoneSessionName)
                ->end()

            ->end()

            ->append($this->errorLogger())
        ;

        //$treeBuilder->setPathSeparator('/');

        return $treeBuilder;
    }

    //###> HELPERS ###

    private function errorLogger()
    {
        $treeBuilder = new TreeBuilder('error_logger_email');

        return $treeBuilder->getRootNode()
            ->info('Set exactly both emails to enable sending emails when prod. Default: donot send emails.')
                ->children()
                ->scalarNode('from')
                    ->info('From this email will be sending letters about errors on this site')
                    ->defaultFalse() # not null to figure out when user doesn't pass value
                ->end()
                ->scalarNode('to')
                    ->info('To this email will be sending letters about errors on this site')
                    ->defaultFalse()
                ->end()
            ->end()
        ;
    }
}
