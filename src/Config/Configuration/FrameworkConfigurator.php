<?php

namespace App\Config\Configuration;

use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use App\Type\Note\NoteType;

class FrameworkConfigurator implements ConfigurationInterface
{
	public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('framework');

		$treeBuilder->getRootNode()
			->children()
			
				->scalarNode('scalar_node')
					->cannotBeOverwritten()
					//->cannotBeEmpty()
					->defaultNull()
					//->defaultValue('en')
				->end()
			
				->booleanNode('boolean_node')
					->defaultFalse()
				->end()
			
				//###> Numeric Node Constraints ###
				->integerNode('integer_node')
					->min(0)->max(100)
					->defaultValue(0)
				->end()
				
				->floatNode('float_node')
					->min(0.0)->max(100.0)
					->defaultValue(0.0)
				->end()
				//###< Numeric Node Constraints ###
			
				->enumNode('enum_node')
					->values(NoteType::TYPES)
				->end()
				
				->arrayNode('user')
					//->setDeprecated('command-bundle', 'v1')
					// #2 entire array_replace(user)
					->performNoDeepMerging()
					//# 1
					->beforeNormalization()
						->ifArray()
						->then(static fn(array $arr) => (\array_values($arr)[0]))
					->end()
					->beforeNormalization()
						->ifString()
						->then(static fn($v) => ['id' => $v])
					->end()
					->children()
						->scalarNode('id')
							->isRequired()
						->end()
						->scalarNode('name')->end()
						->scalarNode('age')->end()
					->end()
				->end()
				
				->arrayNode('array_node')
			
					//->beforeNormalization()->castToArray()->end()
					
					//->normalizeKeys(false)
					
					//###>
					//->addDefaultsIfNotSet()
					
					->useAttributeAsKey('-category_')
					//WORKS WITH ->arrayPrototype()->children...->end()->end()

					//WORKS WITH ->isRequired()
					//->requiresAtLeastOneElement()
					//###<
					
					->arrayPrototype()
					->canBeEnabled()
					//->performNoDeepMerging() // array_replace #    default(array_merge)
					//->addDefaultsIfNotSet()
					->ignoreExtraKeys()
						->children()
							->scalarNode('name')
								->defaultValue('value')
							->end()
							
							->scalarNode('version')
								->defaultValue('value')
							->end()
						->end()
					->end()
				->end()
			
				->arrayNode('scalar_prototype')
					->beforeNormalization()->castToArray()->end()
					->scalarPrototype()->end()
				->end()
			
				/*
				->arrayNode('array_as_an_scalar')
					->beforeNormalization()->castToArray()->end()
				->end()
			
				->variableNode('variable_node')
				->end()
				*/
				
			->end()
		;

        return $treeBuilder;
    }
}