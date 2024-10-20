<?php

namespace App\Tests\Unit\LiveTwigComponent;

use Symfony\UX\LiveComponent\Test\InteractsWithLiveComponents;
use App\Tests\Unit\AbstractUnitCase;
use Symfony\Component\HttpFoundation\Exception\SessionNotFoundException;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use App\Test\DataProvider\CarbonProvider;
use Symfony\Component\Form\Test\TypeTestCase;
use App\Form\Type\YearMonthDayHourMinuteSecondType;
use PHPUnit\Framework\Attributes as PHPUnitAttr;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockFileSessionStorage;

#[PHPUnitAttr\DisableReturnValueGenerationForTestDoubles]
#[PHPUnitAttr\CoversNothing]
class TextareaLiveTwigComponentTest extends AbstractUnitCase
{	
	use InteractsWithLiveComponents;

	#[PHPUnitAttr\Before]
	public function before(): void {
	}
	
	#[PHPUnitAttr\After]
	public function after(): void {
	}
	
	public function testCanRenderAndInteract() {
		$liveComponent = $this->createLiveComponent(
			name: 'live-textarea',
			data: [
				'name' => 'test_form[test_poly]',
				'value' => 'TEST DEFAULT',
			],
		);

		
		$this->assertStringContainsString('name="test_form[test_poly]"', $liveComponent->render());
		
		//$liveComponent->set('value', 'test content');
		//$liveComponent->call('clear');
		$liveComponent->refresh();
		
	}
}