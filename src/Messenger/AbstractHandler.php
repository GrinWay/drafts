<?php

namespace App\Messenger;

use App\Dto\User\UserDto;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\Service\ServiceSubscriberInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\DependencyInjection\Attribute\AutowireLocator;
use Symfony\Component\DependencyInjection\Attribute\TaggedLocator;
use Symfony\Component\DependencyInjection\Attribute\Target;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Contracts\Service\Attribute\SubscribedService;
use App\Contract\Messenger\CommandBusHandlerInterface;
use Symfony\Contracts\Service\ServiceMethodsSubscriberTrait;
use App\Trait\ServiceLocator\Aware\FakerAware;
use App\Trait\ServiceLocator\Aware\LoggerAware;

abstract class AbstractHandler implements CommandBusHandlerInterface, ServiceSubscriberInterface
{
    use ServiceMethodsSubscriberTrait;
    use LoggerAware;
    use FakerAware;

    public function __construct(
        private $messengerServiceLocator,
    ) {
    }

    /* ServiceSubscriberInterface
    public static function getSubscribedServices(): array {
        return [
            new SubscribedService(
                // PROPERTY NAME
                'logger',
                // Service FQCN
                LoggerInterface::class,
                attributes: [
                    //new Autowire('@monolog.logger.messenger.email'),
                    new Target('$messengerEmailLogger'),
                    //new AutowireLocator('app.handler_service'),
                    //new AutowireLocator('app.handler_service'),
                    //new AutowireLocator([
                    //  'logger' => LoggerInterface::class,
                    //]),
                ],
            ),
        ];
    }
    */

    //###> PROTECTED API ###

    #[SubscribedService]
    protected function userDto(): UserDto
    {
        return $this->container->get(__METHOD__);
    }

    protected function service(string $name): ?object
    {
        if (!$this->messengerServiceLocator->has($name)) {
            return null;
        }
        return $this->messengerServiceLocator->get($name);
    }

    //###< PROTECTED API ###
}
