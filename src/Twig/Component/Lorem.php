<?php

namespace App\Twig\Component;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\TwigComponent\Attribute\PreMount;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use App\Repository\ProductRepository;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;
use Symfony\UX\TwigComponent\Attribute\PostMount;

#[AsTwigComponent]
class Lorem
{
	public string $lorem;
	
	public function __construct(
		private $faker,
	) {
	}
	
	public function mount(
		$lorem_len = 30,
	): void {
		$faker = $this->faker;
		$word = static fn() => $faker->word(1);
		
		$lorem = '';
		
		while (0 < $lorem_len) {
			$lorem .= $word() . ' ';
			--$lorem_len;
		}
		
		$this->lorem = $lorem;
	}
}
