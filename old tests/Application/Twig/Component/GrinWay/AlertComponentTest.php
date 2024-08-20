<?php

namespace App\Tests\Application\Twig\Component\GrinWay;

use App\Tests\Application\AbstractApplicationCase;
use Symfony\UX\TwigComponent\Test\InteractsWithTwigComponents;
use Symfony\UX\TwigComponent\AnonymousComponent;

class AlertComponentTest extends AbstractApplicationCase {
    use InteractsWithTwigComponents;
	
	public const string NAME = 'grinway:alert';
	public const string CONTENT = 'block content';
	public const array BLOCKS = [];
	public const array DATA = [
		'alert_type' => 'secondary',
		'alert_alignment' => 'center',
		'alert_position' => 'sticky',
		'alert:id' => '11',
	];
	
	public function testMount(): void {
		$component = $this->mountTwigComponent(
			name: self::NAME,
			data: self::DATA,
		);
		
		$container = self::getContainer();
		$pa = $container->get('property_accessor');
		$type = $pa->getValue($component->getProps(), '[alert_type]');
		$alignment = $pa->getValue($component->getProps(), '[alert_alignment]');
		$position = $pa->getValue($component->getProps(), '[alert_position]');
		$id = $pa->getValue($component->getProps(), '[alert:id]');

		$this->assertInstanceOf(AnonymousComponent::class, $component);
		$this->assertSame('secondary', $type);
		$this->assertSame('center', $alignment);
		$this->assertSame('sticky', $position);
		$this->assertSame('11', $id);
	}
	
	public function testRendered(): void {
		$rendered = $this->renderTwigComponent(
			name: self::NAME,
			data: self::DATA,
			content: self::CONTENT,
			blocks: self::BLOCKS,
		);
		
		$contentBlock = $rendered->crawler()
			->filterXPath('descendant-or-self::div[contains(text(), "block content")]')
		;
		
		$this->assertCount(1, $contentBlock);
		$this->assertSame('block content', $contentBlock->text());
	}
}