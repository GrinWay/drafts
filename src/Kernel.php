<?php

namespace App;

use function Symfony\component\string\u;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\EventDispatcher\DependencyInjection\AddEventAliasesPass;
use App\Contract\Messenger\CommandBusHandlerInterface;
use App\Contract\EventListener\Form\InvokeableFormEventListenerInterface;
use App\Type\Event\ShopEvent;
use App\Messenger\Event\Message\Shop\PriceWasDecreased;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Type\Autowire\AutowireType;
use App\Type\Hash\HashType;
use App\Attribute\AutowireMyMethodOf;
use Symfony\Component\DependencyInjection\Argument\ServiceClosureArgument;
use App\Attribute\NewClosureDefinitionWithTag;
use Symfony\Component\DependencyInjection\Compiler\ServiceLocatorTagPass;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use App\DependencyInjection\Compiler\CustomPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use App\CompillerPass\AppDtoTagPass;
use App\CompillerPass\AutowireMyMethodOfPass;
use App\CompillerPass\RegisterInvokeableFormEventListenerPass;
use App\CompillerPass\NewClosureDefinitionWithTagPass;
use App\CompillerPass\MessengerManagerPass;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use App\Extension\Test\ExtensionExample;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ChildDefinition;
use App\Service\ConfigService;
use Symfony\Contracts\Service\Attribute\Required;

class Kernel extends BaseKernel implements CompilerPassInterface
{
    use MicroKernelTrait;

    public function __construct(
        string $environment,
        bool $debug,
    ) {
        parent::__construct($environment, $debug);
    }

    protected function build(ContainerBuilder $container): void
    {
        $this->addEventAliasesPass($container);

        $container->registerExtension($e = new ExtensionExample());
        $container->loadFromExtension($e->getAlias());
        $container->addCompilerPass(new AppDtoTagPass());
        $container->addCompilerPass(new AutowireMyMethodOfPass());
        //$container->addCompilerPass(new RegisterInvokeableFormEventListenerPass());
        //$container->addCompilerPass(new MessengerManagerPass);


        //TODO (NewClosureDefinitionWithTagPass)
        //processes the tag
        $container->addCompilerPass(new NewClosureDefinitionWithTagPass(
            new NewClosureDefinitionWithTag(HashType::TAG),
            new NewClosureDefinitionWithTag('app.pretty_message'),
        ));

        //TODO (NewClosureDefinitionWithTagPass)
        //sets tag for us, by Attribute
        $container->registerAttributeForAutoconfiguration(
            NewClosureDefinitionWithTag::class,
            static function (
                ChildDefinition $d,
                NewClosureDefinitionWithTag $attr,
                \ReflectionClass|\ReflectionMethod $refl,
            ) {
                $isInvokeable = false;
                $method = null;
                if ($refl instanceof \ReflectionClass) {
                    $isInvokeable = $refl->hasMethod('__invoke');
                }
                if ($refl instanceof \ReflectionMethod) {
                    $isInvokeable = $refl->getDeclaringClass()->hasMethod('__invoke');
                    $method = $refl->getName();
                }
                $tagAttributes = \array_filter(\array_merge(
                    [
                        'method' => $method,
                    ],
                    \array_filter((array) $attr, static fn($e) => !\is_null($e)),
                    [
                        'is_invokeable' => $isInvokeable,
                    ],
                ), static fn($e) => !\is_null($e));
                $d->addTag($attr->tag, $tagAttributes);
            },
        );

        $this->registerAutowireMyMethodOf($container);
    }

    public function process(ContainerBuilder $container): void
    {
        if ('test' === $this->environment) {
            //$container->findDefinition('security.token_storage')->clearTag('kernel.reset');
            //$container->findDefinition('doctrine')->clearTag('kernel.reset');
        }

        $serviceId = 'App\Service\SomeService';

        $ids = $container->findTaggedServiceIds('app.promocode');

        //\dd($ids);

        $d = $container->findDefinition($serviceId);

        /*
        $d
            ->setArgument('$service', new Reference('App\Service\SomeService2'))
        ;
        */

        /*
        \dd(
            ServiceLocatorTagPass::register($container, ['logger' => new Reference('Psr\Log\LoggerInterface $messengerEmailLogger')])
            $d,
            $d->getMethodCalls($serviceId),
            $container->hasDefinition($serviceId),
            $container->has($alias = $serviceId),
            //$container->getDefinition($serviceId),
            $container->findDefinition($alias = $serviceId),
        );
        */
    }

    //###> HELPER ###

    private function registerAutowireMyMethodOf(ContainerBuilder $container): void
    {

        $container->registerAttributeForAutoconfiguration(
            AutowireMyMethodOf::class,
            static function (
                ChildDefinition $d,
                AutowireMyMethodOf $attr,
                \ReflectionParameter $refl,
            ) {
                $parameterName = $refl->getName();
                $typeOfParameter = $refl->getType()?->getName();
                $id = $attr->id;

                $tag = AutowireType::AUTOWIRE_METHOD_OF;
                $tagAttributes = [
                    'attribute_id' => $id,
                    'type_of_parameter' => $typeOfParameter,
                    'parameter_name' => $parameterName,
                ];
                $d->addTag($tag, $tagAttributes);
            },
        );
    }

    private function addEventAliasesPass(ContainerBuilder $container): void
    {
        return;

        $container->addCompilerPass(new AddEventAliasesPass([
            PriceWasDecreased::class => ShopEvent::PRICE_WAS_DECREASED,
        ]));
    }
}
