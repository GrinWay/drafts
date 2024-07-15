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

#[AsTwigComponent('grin_way_watch', template: '@GSGenericParts/components/grin_way_watch.html.twig')]
class GSWatchComponent extends AbstractTwigComponent
{
    public $intervalMs          = 1000;
    public $attr                = [
        'class'     => 'fs-6 d-inline badge text-bg-dark',
        'style'     => "font-family: gs-default, times-new-roman;",
    ];

    public function mount(
        ?array $attr = null,
    ) {
        $this->assignAttr($attr);
    }

    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefined([
                'intervalMs',
                'attr',
            ])
        ;
    }

    //###> HELPER ###
    private function assignAttr(?array $attr): void
    {
        $attr   ??= [];
        $attr   = \array_replace($this->attr, $attr);

        $this->attr             = $attr;
    }
}
