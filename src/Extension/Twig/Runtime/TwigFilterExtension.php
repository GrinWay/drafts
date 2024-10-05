<?php

namespace App\Extension\Twig\Runtime;

use  function Symfony\Component\String\u;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Twig\Extension\RuntimeExtensionInterface;

class TwigFilterExtension implements RuntimeExtensionInterface
{
    public function __construct(
        #[Autowire(service: RequestStack::class)]
        private readonly RequestStack $requestStack,
        #[Autowire('%env(APP_LOCALE)%')]
        private readonly string $defaultLocale,
    ) {
    }

    public function forUser(\DateTime|\DateTimeImmutable $date, string $tz, ?string $locale = null, ?string $isoFormat = null, bool $throw = true): string
    {
        //###> LOCALE
        $locale ??= $this->requestStack->getCurrentRequest()?->getLocale();
        if ($throw) {
            if (null === $locale) {
                throw new \Exception('Locale wasn\'t set from current request. Pass the locale argument explicitly.');
            }
        } else {
            if (null === $locale) {
                $locale = $this->defaultLocale;
            }
        }

        //###> TIMEZONE
        $isoFormat ??= 'LLLL';

        if ($date instanceof \DateTime) {
            $carbon = new Carbon($date);
        } else {
            $carbon = new CarbonImmutable($date);
        }

        $carbon = $carbon->tz($tz)->locale($locale);
        return '' . u($carbon->isoFormat($isoFormat))->title();
    }
	
	//TODO: attributesAsArray -> renamed as stringAttributeAsArray
	/**
	 * Usage:
	 * 
	 * In twig template for <twig> tags
	 * <twig {{ ...normalAttributeVariable|THIS_FILTER }}></twig>
	 */
	public function stringAttributeAsArray(string $attribute, string $delimiter = '='): array {
		
		$exploded = \explode($delimiter, $attribute);
		
		if (2 !== \count($exploded)) {
			$m = \sprintf(
				'The string: "%s" must contain exactly one: "%s" sign',
				$attribute,
				$delimiter,
			);
			throw new \LogicException($m);
		}
		
		$result = [
			\array_shift($exploded) => \trim(\array_pop($exploded), " \n\r\t\v\x00\"\'"),
		];
		
		return $result;
	}
}
