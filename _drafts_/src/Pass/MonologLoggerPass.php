<?php

namespace GrinWay\GenericParts\Pass;

use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\Mime\Email;
use GrinWay\GenericParts\Service\{
    GrinWay\Service\Service
};
use GrinWay\GenericParts\GSGenericPartsExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\{
	Parameter,
	Reference
};

class MonologLoggerPass implements CompilerPassInterface
{
    public const EMAIL_ERROR_HANDLER_ID     = 'monolog.handler.grin_way_generic_parts_admin_email_error_logger';

    public function __construct()
    {
    }

    public function process(ContainerBuilder $container)
    {
        $this->customizeOrNullInternalMonologLoggerMailerHandler($container);
    }

    // ###> HELPER ###

    private function customizeOrNullInternalMonologLoggerMailerHandler(
        ContainerBuilder $container
    ): void {
        $emailFrom                          = $container->getParameter(
            new Parameter(GrinWay\Service\Service::getParameterName(
				GSGenericPartsExtension::PREFIX,
				GSGenericPartsExtension::ERROR_LOGGER_EMAIL_FROM,
			))
        );
        $emailTo                            = $container->getParameter(
            new Parameter(GrinWay\Service\Service::getParameterName(
				GSGenericPartsExtension::PREFIX,
				GSGenericPartsExtension::ERROR_LOGGER_EMAIL_TO,
			))
        );
        /*
        \dd(
            $emailFrom,
            $emailTo,
            $container->hasDefinition(self::EMAIL_ERROR_HANDLER_ID),
        );
        */

        if (!$container->hasDefinition(self::EMAIL_ERROR_HANDLER_ID)) {
            return;
        }

        if ($emailFrom && $emailTo) {
            /**
                Set correct email from and to
            */
            $emailLoggerDefinition = $container->getDefinition(self::EMAIL_ERROR_HANDLER_ID);
            $currentEmail       = $emailLoggerDefinition->getArgument($mailerHandlerArgument = 1);
            $expectedClass      = Email::class;

            if (!\is_callable($currentEmail) && !$currentEmail instanceof Definition && $expectedClass != $currentEmail->getClass()) {
                throw new \Exception('It was expected that ' . $mailerHandlerArgument . ' argument of ' . $emailLoggerDefinition->getClass() . ' __construct will be a ' . $expectedClass . ' class or callable');
            }

            $currentCalls       = $currentEmail->getMethodCalls();
            $newCalls           = \array_replace_recursive($currentCalls, [
                [
                    'from',
                    [$emailFrom],
                ],
                [
                    'to',
                    [$emailTo],
                ],
            ]);
            $currentEmail->setMethodCalls($newCalls);

            $emailLoggerDefinition
                ->setArgument(
                    $mailerHandlerArgument,
                    $currentEmail,
                )
            ;
        } else {
            /* 'monolog.handler.null_internal' for email error logger handler
            */
            $container->setAlias(
                self::EMAIL_ERROR_HANDLER_ID,       # this service
                'monolog.handler.null_internal',    # points to this service
            );
        }
    }
}
