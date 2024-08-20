<?php

namespace GrinWay\GenericParts\Twig\Component;

use function Symfony\Component\String\u;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\Validator\{
    Constraints,
    Validation
};
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\TwigComponent\Attribute\{
    ExposeInTemplate,
    PreMount,
    PostMount,
    AsTwigComponent
};

#[AsTwigComponent('grin_way_alert', template: '@GSGenericParts/components/grin_way_alert.html.twig')]
class GSAlertComponent extends AbstractTwigComponent
{
    public string $message;
	
    public int $delay					= 3000;
    public bool $persist				= false;

    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
			->setDefaults([
				'delay'			=> $this->delay,
				'persist'		=> $this->persist,
			])
            ->setRequired([
                'message',
            ])
        ;
    }
}
