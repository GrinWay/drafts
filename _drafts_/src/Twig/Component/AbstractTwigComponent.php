<?php

namespace GrinWay\GenericParts\Twig\Component;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\TwigComponent\Attribute\{
    ExposeInTemplate,
    PreMount,
    PostMount,
    AsTwigComponent
};

abstract class AbstractTwigComponent
{
    /* params which you set by your self
    public function mount(
    ) {}
    */

    #[preMount(priority: 0)]
    public function preMount(array $data)
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);
        return $resolver->resolve($data);
    }

    // ###> ABSTRACT ###
    abstract protected function configureOptions(OptionsResolver $resolver): void;
    // ###< ABSTRACT ###
}
