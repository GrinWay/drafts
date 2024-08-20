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

#[AsTwigComponent('grin_way_navs', template: '@GSGenericParts/components/grin_way_navs.html.twig')]
class GSNavsComponent extends AbstractTwigComponent
{
    public array $options = [
        // only one required
        'route'             =>  null,
        'url'               =>  null,

        'attributes'        =>  [],
        'show'              =>  true,
        'oneClick'          =>  false,
        'role'              =>  'PUBLIC_ACCESS',
        'class'             =>  'btn btn-outline-success',
        'query'             =>  [],
        'form'              =>  [
            'data'      => null,
            'options'   => [],
        ],
    ];

    public function mount(
        array $options,
    ) {
        $this->setDefaults($options);
    }

    // >>> helpers >>>

    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'options' => function (
                    OptionsResolver $optionslResolver,
                    Options $parent
                ) {
                    $optionslResolver
                        ->setPrototype(true) // \is_array(option[0]), \is_array(option[1]), ...
                        ->setDefaults([
                            'form' => function (OptionsResolver $formResolver, Options $parent) {
                                $formResolver
                                    ->setDefaults($this->options['form'])
                                    ->setDefined([
                                        'type',
                                    ])
                                    ->setAllowedTypes('type', ['string'])
                                    ->setAllowedTypes('options', ['array'])
                                ;
                            },
                            'confirm' => static function (OptionsResolver $formResolver, Options $parent) {
                                $formResolver
                                    ->setDefined([
                                        'message',
                                        'text',
                                        'icon',
                                        'showCancelButton',

                                        'successIcon',
                                        'failureIcon',
                                        'successMessage',
                                        'failureMessage',
                                    ])
                                    ->setAllowedTypes('text', ['string'])
                                    ->setAllowedTypes('icon', ['string'])
                                    ->setAllowedTypes('successIcon', ['string'])
                                    ->setAllowedTypes('failureIcon', ['string'])
                                    ->setAllowedTypes('showCancelButton', ['boolean'])
                                    //
                                    ->setAllowedValues(
                                        'icon',
                                        [
                                            'success',
                                            'error',
                                            'warning',
                                            'info',
                                            'question',
                                        ]
                                    )
                                    ->setAllowedValues(
                                        'successIcon',
                                        [
                                            'success',
                                            'warning',
                                            'info',
                                            'question',
                                        ]
                                    )
                                    ->setAllowedValues(
                                        'failureIcon',
                                        [
                                            'error',
                                            'warning',
                                            'info',
                                            'question',
                                        ]
                                    )
                                ;
                            },
                        ])
                        ->setRequired([
                            //'route',
                        ])
                        ->setDefined([
                            'name',
                        ])
                        ->setDefaults($this->options)
                        ->setAllowedTypes('query', 'array')
                    ;
                },
            ])
            //
            ->setAllowedTypes('options', 'array')
            //
        ;
    }

    // >>>

    private function setDefaults(array $passedOptions)
    {
        $this->options = \array_map(
            fn($childOptions) => \array_replace_recursive(
                [
                    'name'      =>  $childOptions['name'] ?? $childOptions['route'],
                ],
                $this->options,
                $childOptions,
            ),
            $passedOptions,
        );
        //\dd($passedOptions, $this->options);
    }
}
