<?php

namespace App\Tests\Unit\Form\Type;

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
#[PHPUnitAttr\CoversClass(YearMonthDayHourMinuteSecondType::class)]
class YearMonthDayHourMinuteSecondTypeTest extends AbstractUnitCase
{	
	#[PHPUnitAttr\Before]
	public function before(): void {
	}
	
	#[PHPUnitAttr\After]
	public function after(): void {
	}
	
	#[PHPUnitAttr\DataProviderExternal(CarbonProvider::class, 'carbonAndCarbonImmutable')]
	public function testPayloadDataSetToFormAndToOriginObjectTooIfOnlyThatIsSameObject(string $carbonClass) {
		$container = self::getContainer();
		$factory = $container->get('form.factory');
		
		//###> DATA ###
		$year = 1970;
		$month = 01;
		$day = 2;
		$hour = 11;
		$minute = 12;
		$second = 42;
		//###< DATA ###
		
		$originModel = new $carbonClass("0000-01-01 00:00:00");
		$originModelId = \spl_object_id($originModel);
		
		$form = $factory->create(YearMonthDayHourMinuteSecondType::class, $originModel, options: [
			'validation_groups' => [
				'date',
				'time',
			],
		]);
		
		$payload = [
			'year' => $year,
			'month' => $month,
			'day' => $day,
			'hour' => $hour,
			'minute' => $minute,
			'second' => $second,
		];
		$form->submit($payload);
		
		$expectedModel = new $carbonClass("${year}-${month}-${day} ${hour}:${minute}:${second}");

		//###>
		$this->assertInstanceOf(\get_debug_type($expectedModel), $originModel);
		
		$this->assertSame($originModelId, \spl_object_id($originModel), message: 'Origin object is not the same object.');
		if ($originModel instanceof CarbonImmutable) {
			$this->assertNotEquals($expectedModel, $originModel);
			$this->assertEquals($expectedModel, $form->getData());
		} else {
			$this->assertEquals($expectedModel, $originModel);
		}
		
		$this->assertTrue($form->isSynchronized(), 'transformer throws an Exception');
		$this->assertTrue($form->isSubmitted(), 'is not submitted');
		//$this->assertTrue($form->isValid(), 'is not valid');
		//###<
		
		return $form;
	}
	
	public function testFormViewVars() {
		$container = self::getContainer();
		$factory = $container->get('form.factory');
		
		$formView = $factory->create(YearMonthDayHourMinuteSecondType::class)->createView();
		
		$this->assertSame('POST', $formView->vars['method']);
	}
	
	public function testFormRendering() {
		$container = self::getContainer();
		$twig = $container->get('twig');
		$factory = $container->get('form.factory');
		
		$form = $factory->create(YearMonthDayHourMinuteSecondType::class);
		$formView = $form->createView();
		
		$content = <<<'__T__'
			{{- form_start(form) -}}
				{{- form_errors(form) -}}
				{{- form_label(form) -}}
				{{- form_widget(form) -}}
				{{- form_help(form) -}}
			{{- form_end(form, {render_rest: false}) -}}
		__T__;
		
		$renderedFrom = $twig->createTemplate($content)->render(['form' => $formView]);
		
		$fullNameAttr = static fn($name) => 'name="'.$form->getName().'['.$name.']"';
		
		$this->assertStringContainsStringIgnoringCase('form', $renderedFrom);
		$this->assertStringContainsStringIgnoringCase($fullNameAttr('year'), $renderedFrom);
		$this->assertStringContainsStringIgnoringCase($fullNameAttr('month'), $renderedFrom);
		$this->assertStringContainsStringIgnoringCase($fullNameAttr('day'), $renderedFrom);
		$this->assertStringContainsStringIgnoringCase($fullNameAttr('hour'), $renderedFrom);
		$this->assertStringContainsStringIgnoringCase($fullNameAttr('minute'), $renderedFrom);
		$this->assertStringContainsStringIgnoringCase($fullNameAttr('second'), $renderedFrom);
	}
	
}