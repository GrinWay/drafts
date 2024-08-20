<?php

namespace App\CompillerPass;

use App\Attribute\NewClosureDefinitionWithTag;
use App\Attribute\Hash\HashGetter;
use App\Type\Hash\HashType;
use App\Messenger\MessengerManager;
use Symfony\Component\DependencyInjection\Reference;

use function Symfony\component\string\u;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use App\Extractor\DefaultValueExtractor;
use Symfony\Component\DependencyInjection\Argument\ServiceClosureArgument;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Alias;

//TODO (NewClosureDefinitionWithTagPass)
class NewClosureDefinitionWithTagPass extends AbstractCompilerPass
{
    protected readonly array $newClosureDefinitionsWithTag;

    public const DEFAULT_INDEX_BY_NAME = 'index';

    /**
    * Services that use #[NewClosureDefinitionsWithTag(tag)] leave without defined tag
    * But a new \Closure Definition creates with this tag
    * and references to the method of the origin service.
    */
    public function __construct(
        NewClosureDefinitionWithTag ...$newClosureDefinitionsWithTag,
    ) {
        $this->newClosureDefinitionsWithTag = $newClosureDefinitionsWithTag;
    }

    public function process(ContainerBuilder $container): void
    {
        foreach ($this->newClosureDefinitionsWithTag as $newClosureDefinitionWithTag) {
            [
                'tag' => $tag,
                'index' => $indexAsName,
            ] = ((array) $newClosureDefinitionWithTag);
            $indexAsName ??= self::DEFAULT_INDEX_BY_NAME;
            $ids = $container->findTaggedServiceIds($tag);

            foreach ($ids as $id => $originTagAttributes) {
                $originDefinition = $container->findDefinition($id);
                if (null === $originDefinition) {
                    continue;
                }

                $originClass = $originDefinition->getClass();
                $originDefinition->clearTag($tag);

                $index = self::getFirstTagAttribute('index', $originTagAttributes);
                $method = self::getFirstTagAttribute('method', $originTagAttributes);
                $isInvokeable = self::getFirstTagAttribute('is_invokeable', $originTagAttributes);
                $method ??= '__invoke';

                if ('__invoke' === $method && !$isInvokeable) {
                    throw new \Exception(
                        \sprintf(
                            'The class: "%s" must have "%s" method, or pass "%s" argument to the "%s" attribute',
                            $originClass,
                            $method,
                            'method',
                            HashGetter::class,
                        ),
                    );
                }
                $callableMethodOfService = [new Reference($originClass), $method];

                $pureTagAttributes = [];
                $class = 'Closure';
                $factory = 'Closure::fromCallable';
                if (null !== $index) {
                    $pureTagAttributes[$indexAsName] = $index;
                }
                $tags = [
                    $tag => [
                        $pureTagAttributes,
                    ],
                ];
                $arguments = [
                    $callableMethodOfService,
                ];
                $newDefinition = (new Definition($class))
                    ->setFactory($factory)
                    ->setArguments($arguments)
                    ->setTags($tags)
                ;
                $newDefinitionId = \strtolower(\preg_replace('~\\\~', '_', $originClass) . '.' . $class . '.' . $method);
                $container->setDefinition($newDefinitionId, $newDefinition);
            }
        }
    }

    //###> HELPER ###
}
