<?php

namespace App\Bundle;

use App\Type\Note\NoteType;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use Symfony\Component\PropertyAccess\PropertyAccess;

class AppTestBundle extends AbstractBundle
{
    /**
     * @return void
     */
    public function boot()
    {
		parent::boot();
		
		//\dd(__METHOD__);
    }

    /**
     * @return void
     */
    public function shutdown()
    {
		parent::boot();
		
		//\dd(__METHOD__);
    }
	
	public function configure(DefinitionConfigurator $definition): void
    {
		//\dd(__METHOD__); // 1
        $definition->rootNode()
            ->children()
				->arrayNode('database')
					->fixXMLConfig('user', 'users')
					->children()
						
						->integerNode('max_connections')
							->min(0)
							->max(100)
							//->defaultValue(50)
						->end()
						
						->booleanNode('auto_connect')
							->defaultTrue()
						->end()
						
						->scalarNode('default_connection')
							->treatNullLike('mysql')
							->defaultNull()
							->cannotBeEmpty()
						->end()
						
						->arrayNode('users')
							->scalarPrototype()->end()
						->end()
						
						->arrayNode('mysql_settings')
							->ignoreExtraKeys()
							->children()
								->scalarNode('host')->end()
							->end()
						->end()
						
						->arrayNode('connections')
						
							->beforeNormalization()
								->ifArray()
								->then(static function($passedPrototype) {
									
									foreach($passedPrototype as &$array) {
										if (true
											&& isset($array['driver'])
											&& isset($array['memory'])
											&& 'sqlite' !== $array['driver']
										) {
											$message = \sprintf(
												'The key "%s" can be included if only driver equals "%s"',
												'memory',
												'sqlite',
											);
											throw new \LogicException($message);
										}										
									}
									
									return $passedPrototype;
								})
							->end()
							
							->useAttributeAsKey('driver', removeKeyItem: false)
							->normalizeKeys(false)
							->requiresAtLeastOneElement()
							->arrayPrototype()
								->addDefaultsIfNotSet()
								//->ignoreExtraKeys()
								->children()
									->scalarNode('host')->end()
									->scalarNode('driver')
										->isRequired()
									->end()
									->scalarNode('username')->end()

									//###2> CORRECT: default after validation
									->booleanNode('memory')
										->defaultNull()
									->end()

									->scalarNode('password')->end()
								->end()
							->end()
						->end()
						
						->arrayNode('key')
							->arrayPrototype()
								//###3> castToArray for scalarPrototype
								->beforeNormalization()->castToArray()->end()
								->scalarPrototype()->end()
							->end()
						->end()
						
					->end()
				->end()				
            ->end()
        ;
    }

    public function prependExtension(ContainerConfigurator $container, ContainerBuilder $builder): void
    {
		/*
		$builder->prependExtensionConfig('grin_way_bundle_share_settings', []);
		$container->import('../../config/config/test.yaml');
		$container->extension('grin_way_bundle_share_settings', [
			'one' => 11,
		], prepend: true);
		*/
    }

	public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
		$pa = PropertyAccess::createPropertyAccessor();
		
		/*
		\dd(
			$config,
			//$pa->getValue($config, '[twitter][client_id]'),
		); // 2
		*/
    }

}