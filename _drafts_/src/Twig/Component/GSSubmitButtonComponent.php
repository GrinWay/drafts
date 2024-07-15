<?php

namespace GrinWay\GenericParts\Twig\Component;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
use Symfony\UX\LiveComponent\{
    Attribute\AsLiveComponent,
    Attribute\LiveProp,
    Attribute\LiveArg,
    Attribute\LiveAction,
    DefaultActionTrait
};

#[AsTwigComponent('grin_way_submit_button', template: '@GSGenericParts/components/grin_way_submit_button.html.twig')]
class GSSubmitButtonComponent extends AbstractTwigComponent
{
    public string $class        = 'btn btn-outline-primary';
    public array $attr          = [];
    public string $text;

    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
			->setDefined([
				'class',
				'attr',
			])
            ->setRequired([
                'text',
            ])
        ;
	}
 
	// ###> HELPER ###

}
