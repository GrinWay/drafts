<?php

namespace App\Service;

use Symfony\Bundle\FrameworkBundle\Controller\TemplateController;
use Symfony\Component\HttpKernel\Controller\ControllerReference;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Fragment\FragmentUriGeneratorInterface;

//TODO: FragmentUtils
class FragmentUtils
{
    public function __construct(
        private readonly FragmentUriGeneratorInterface $fragmentUriGenerator,
        private readonly RequestStack $requestStack,
    ) {
    }

    //###> API ###
    /**
    * @return string (TemplateController _fragment uri)
    */
    public function templateUri(
        string $template,
    ): string {
        return $this->generate(
            controller: TemplateController::class,
            attributes: [
                'template' => $template,
            ],
            absolute: true,
        );
    }

    /**
    * @return string (_fragment uri)
    */
    public function generate(
        string $controller,
        array $attributes,
        bool $absolute = true,
    ): string {
        $controllerRef = new ControllerReference(
            controller: $controller,
            attributes: $attributes,
        );
        $request = $this->requestStack->getCurrentRequest();
        return $this->fragmentUriGenerator->generate(
            $controllerRef,
            $request,
            absolute: $absolute,
        );
    }
    //###< API ###
}
