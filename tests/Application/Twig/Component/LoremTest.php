<?php

namespace App\Tests\Application\Twig\Component;

use Symfony\Component\Validator\Validation;
use App\Tests\Application\AbstractApplicationCase;
use Symfony\UX\TwigComponent\Test\InteractsWithTwigComponents;
use App\Twig\Component\Lorem;
use Symfony\Component\Validator\Constraints;
use App\Validation\Constraint\WordCount;
use App\Service;

class LoremTest extends AbstractApplicationCase {
	use InteractsWithTwigComponents;
	
	public const string NAME = 'Lorem';
	public const array DATA = [
		'lorem_len' => '101',
	];
	
	private $t = null;
	private $twig = null;
	
	protected function setUp(): void {
		parent::setUp();
		$this->t ??= self::getContainer()->get('translator');
		$this->twig ??= self::getContainer()->get('twig');
		//\dump(__METHOD__);
	}
	
	protected function tearDown(): void {
		parent::tearDown();
		//\dump(__METHOD__);
	}
	
	public function testMount(): void {
		$component = $this->mountTwigComponent(
			name: self::NAME,
			data: self::DATA,
		);
		
		$requiredWords = self::DATA['lorem_len'];
		$passedWords = Service\WordUtil::countWords($component->lorem);
		$wordCountCheck = Validation::createIsValidCallable(
			new WordCount(min: $requiredWords, max: $requiredWords),
		);
		
		$this->assertInstanceOf(Lorem::class, $component, message: $this->t->trans('instance_of', [
			'{class}' => Lorem::class,
		], 'app.test'));
		$this->assertTrue($wordCountCheck($component->lorem), message: $this->t->trans('word_count', [
			'{passed}' => $passedWords,
			'{required}' => $requiredWords,
		], 'app.test'));
	}
	
	public function testRendered(): void {
		$rendered = $this->renderTwigComponent(
			name: self::NAME,
			data: self::DATA,
		);
		
		$crawler = $rendered->crawler();
		$loremElements = $crawler->filter('.app-lorem');
		
		$this->assertCount(1, $loremElements);
		$this->assertTrue($this->twig->getLoader()->exists('components/Lorem.html.twig'));
	}
}