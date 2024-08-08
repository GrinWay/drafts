<?php

namespace App\Tests\Application\Twig\Component;

use Symfony\Component\Validator\Validation;
use App\Tests\Application\AbstractApplicationCase;
use Symfony\UX\TwigComponent\Test\InteractsWithTwigComponents;
use App\Twig\Component\Lorem;
use Symfony\Component\Validator\Constraints;
use App\Validation\Constraint\WordCount;

class LoremTest extends AbstractApplicationCase {
	use InteractsWithTwigComponents;
	
	public const string NAME = 'Lorem';
	public const array DATA = [
		'lorem_len' => '101',
	];
	
	public function testMount(): void {
		$component = $this->mountTwigComponent(
			name: self::NAME,
			data: self::DATA,
		);
		
		$wordCountCheck = Validation::createIsValidCallable(
			new WordCount(min: 101, max: 101),
		);
		
		$this->assertInstanceOf(Lorem::class, $component);
		$this->assertTrue($wordCountCheck($component->lorem));
	}
	
	public function testRendered(): void {
		$rendered = $this->renderTwigComponent(
			name: self::NAME,
			data: self::DATA,
		);
		
		
	}
}