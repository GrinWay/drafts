<?php

namespace App\Twig\Component;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\TwigComponent\Attribute\PreMount;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use App\Repository\ProductRepository;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;
use Symfony\UX\TwigComponent\Attribute\PostMount;

#[AsTwigComponent(name: 'products')]
class Products
{
    #[ExposeInTemplate(getter: 'getProducts')]
    private array $products;

    private $someVal;

    public function __construct(
        private readonly ProductRepository $producRepository,
    ) {
        $this->products = [];
    }

    public function mount(
        $_ = null,
    ): void {
        $this->someVal = \rand(0, 11);
    }

    public function getProducts(): array
    {
        return $this->producRepository->findAll();
    }
}
