<?php

namespace App\CompillerPass;

use function Symfony\component\string\u;

use Symfony\Component\DependencyInjection\Argument\BoundArgument;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Attribute\AutowireMyMethodOf;
use App\Type\Autowire\AutowireType;
use App\Attribute\NewClosureDefinitionWithTag;
use App\Attribute\Hash\HashGetter;
use App\Type\Hash\HashType;
use App\Messenger\MessengerManager;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use App\Extractor\DefaultValueExtractor;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Alias;

class AutowireMyMethodOfPass extends AbstractCompilerPass
{
    public function __construct()
    {
    }

	/**
	* @return void
	*/
    public function process(ContainerBuilder $container): void
    {

        $ids = $container->findTaggedServiceIds(AutowireType::AUTOWIRE_METHOD_OF);

        foreach ($ids as $id => $originTagAttributes) {
            $parameterName = self::getFirstTagAttribute('parameter_name', $originTagAttributes);
            $typeOfParameter = self::getFirstTagAttribute('type_of_parameter', $originTagAttributes);
            $attributeId = self::getFirstTagAttribute('attribute_id', $originTagAttributes);
            //$ = self::getFirstTagAttribute('', $originTagAttributes);

            $d = $container->findDefinition($id);

            if (null === $attributeId) {
                throw new \Exception(\sprintf(
                    'Class "%s" has a dependency "%s" but attribute "%s" has no "id" argument and type hint of parameter was not provided',
                    $d->getClass(),
                    $parameterName,
                    AutowireMyMethodOf::class,
                ));
            }

            if (null !== $typeOfParameter && \Closure::class !== $typeOfParameter) {
                throw new \Exception(\sprintf(
                    'Type of parameter: "%s" must be \\Closure or just ommit the type',
                    $parameterName,
                ));
            }

            if (!\method_exists($attributeId, $parameterName)) {
                throw new \Exception(\sprintf(
                    'Class: "%s" does not have method "%s" but must have',
                    $typeOfParameter,
                    $parameterName,
                ));
            }

            $methodClosure = [
                new Reference($attributeId),
                $parameterName,
            ];

            $newD = (new Definition('Closure'))
                ->setFactory('Closure::fromCallable')
                ->setArgument(0, $methodClosure)
            ;

            $d->setArgument(
                '$' . $parameterName,
                $newD,
            );
        }
    }

    //###> HELPER ###
}
