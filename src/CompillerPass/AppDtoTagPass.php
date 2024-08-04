<?php

namespace App\CompillerPass;

use Symfony\Component\DependencyInjection\Reference;

use function Symfony\component\string\u;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use App\Extractor\DefaultValueExtractor;

class AppDtoTagPass implements CompilerPassInterface
{
    private readonly PropertyInfoExtractor $propInfoExtractor;

    public function __construct()
    {
        $reflectionExtractor = new ReflectionExtractor();

        $extractors = [$reflectionExtractor];
        $defaultValueExtractor = new DefaultValueExtractor();

        $this->propInfoExtractor = new PropertyInfoExtractor(
            listExtractors: $extractors,
            typeExtractors: [$defaultValueExtractor/*, ...$extractors*/],
            descriptionExtractors: $extractors,
            accessExtractors: $extractors,
            initializableExtractors: $extractors,
        );
    }

	/**
	* @return void
	*/
    public function process(ContainerBuilder $container): void
    {

        $ids = $container->findTaggedServiceIds('app.dto');

        foreach ($ids as $id => $dopTags) {
            $definition = $container->getDefinition($id);
            $props = $this->propInfoExtractor->getProperties($class = $definition->getClass());

            $propNameValues = [];
            foreach ($props as $propName) {
                $doc = $this->propInfoExtractor->getTypes($class, $propName);
                if ($doc === null || \is_array($doc) && \count($doc) < 1) {
                    continue;
                }

                $originValue = \array_shift($doc);
                $value = '' . u($originValue)->ensureEnd(';')->ensureStart('return ');
                try {
                    $value = eval($value);
                } catch (\Exception $e) {
                    $value = $originValue;
                }
                $propNameValues[$propName] = $value;
            }

            if (!empty($propNameValues)) {
                $definition->setProperties($propNameValues);
            }
        }
    }
}
