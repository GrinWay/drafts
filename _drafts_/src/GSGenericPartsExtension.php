<?php

namespace GrinWay\GenericParts;

use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\DependencyInjection\Definition;
use GrinWay\GenericParts\Service\{
    GrinWay\Service\Service,
    StringNormalizer
};
use GrinWay\GenericParts\Configuration;
use GrinWay\GenericParts\Contracts\GrinWayTag;
use Symfony\Component\DependencyInjection\{
	Parameter,
	Reference
};
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\{
    YamlFileLoader
};
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use GrinWay\Service\Service\{
    ServiceContainer,
    StringNormalizer
};

class GSGenericPartsExtension extends ConfigurableExtension implements PrependExtensionInterface
{
    public const PREFIX						= 'grin_way_generic_parts';
	
	/*	bundle config -> paramter
	*/
	
    public const LOCALE = 'locale';
    public const TIMEZONE = 'timezone';
    public const TIMEZONE_SESSION_NAME = 'timezone_session_name';
	
    public const EMAIL_ERROR_LOGGER_ROOT_KEY = 'email_error_logger';
    public const EMAIL_ERROR_LOGGER_FROM_KEY = 'from';
    public const EMAIL_ERROR_LOGGER_TO_KEY = 'to';
    
	public const EMAIL_ERROR_LOGGER_PA_FROM_KEY = ''
		. '[' . self::EMAIL_ERROR_LOGGER_ROOT_KEY . ']'
		. '[' . self::EMAIL_ERROR_LOGGER_FROM_KEY . ']'
	;
    public const EMAIL_ERROR_LOGGER_PA_TO_KEY = ''
		. '[' . self::EMAIL_ERROR_LOGGER_ROOT_KEY . ']'
		. '[' . self::EMAIL_ERROR_LOGGER_TO_KEY . ']'
	;
    
	public function getAlias(): string
    {
		return self::PREFIX;
	}

    /**
        -   load packages .yaml
    */
    public function prepend(ContainerBuilder $container)
    {
        $this->loadYaml($container, [
            ['config/packages',     'messenger.yaml'],
            ['config/packages',     'framework.yaml'],
            ['config/packages',     'translation.yaml'],
            ['config',              'services.yaml'],
            // needs services.yaml
            ['config/packages',     'monolog.yaml'],
            ['config/packages',     'twig.yaml'],
        ]);
    }

    public function getConfiguration(
        array $config,
        ContainerBuilder $container,
    ) {
        return new Configuration(
			timezoneSessionName: $container->getParameter(ServiceContainer::getParameterName(
				self::PREFIX,
				self::TIMEZONE_SESSION_NAME,
			)),
		);
    }

    /**
        -   load services.yaml
        -   config->services
        -   bundle's tags
    */
    public function loadInternal(array $config, ContainerBuilder $container)
    {
        $this->loadYaml($container, [
            //['config',                    'services.yaml'],
        ]);
        $this->fillInParameters($config, $container);
        $this->fillInServiceArgumentsWithConfigOfCurrentBundle($config, $container);
        $this->registerBundleTagsForAutoconfiguration($container);
        /*
        \dd(
            $container->getParameter('grin_way_generic_parts.timezone'),
        );
        */
    }

    //###> HELPERS ###

    private function fillInParameters(
        array $config,
        ContainerBuilder $container,
    ) {
        /*
        \dd(
            $container->hasParameter('error_prod_logger_email'),
            PropertyAccess::createPropertyAccessor()->getValue($config, '[error_prod_logger_email][from]'),
        );
        */

		$pa = PropertyAccess::createPropertyAccessor();
        GrinWay\Service\Service::setParametersForce(
            $container,
            callbackGetValue:       static function ($key) use (&$config, $pa) {
                return $pa->getValue($config, $key);
            },
            parameterPrefix: self::PREFIX,
            keys: [
                '['.self::LOCALE.']',
                '['.self::TIMEZONE.']',
                self::EMAIL_ERROR_LOGGER_PA_FROM_KEY,
                self::EMAIL_ERROR_LOGGER_PA_TO_KEY,
            ],
        );
		
		/* to use in this object
		$this->localeParameter = new Parameter(ServiceContainer::getParameterName(
			self::PREFIX,
			self::LOCALE,
		));
		$this->timezoneParameter = new Parameter(ServiceContainer::getParameterName(
			self::PREFIX,
			self::TIMEZONE,
		));
		*/
    }

    private function fillInServiceArgumentsWithConfigOfCurrentBundle(
        array $config,
        ContainerBuilder $container,
    ) {
    }

    private function registerBundleTagsForAutoconfiguration(ContainerBuilder $container)
    {
        /*
        $container
            ->registerForAutoconfiguration(\GrinWay\GenericParts\<>Interface::class)
            ->addTag(GrinWayTag::<>)
        ;
        */
    }

    /**
        @var    $relPath is a relPath or array with the following structure:
            [
                ['relPath', 'filename'],
                ...
            ]
    */
    private function loadYaml(
        ContainerBuilder $container,
        string|array $relPath,
        ?string $filename = null,
    ): void {

        if (\is_array($relPath)) {
            foreach ($relPath as [$path, $filename]) {
                $this->loadYaml($container, $path, $filename);
            }
            return;
        }

        if (\is_string($relPath) && $filename === null) {
            throw new \Exception('Incorrect method arguments');
        }

        $loader = new YamlFileLoader(
            $container,
            new FileLocator(
                [
                    __DIR__ . '/../' . $relPath,
                ],
            ),
        );
        $loader->load($filename);
    }
}
