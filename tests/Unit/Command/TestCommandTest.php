<?php

namespace App\Tests\Unit\Command;

use Symfony\Component\Console\Helper\HelperSet;
use App\Tests\Unit\AbstractUnitCase;
use Symfony\Component\Console\Tester\CommandTester;
use PHPUnit\Framework\Attributes as PHPUnitAttr;
use App\Command\TestCommand;
use Symfony\Component\Console\Helper\FormatterHelper;
use Symfony\Component\Console\Command\Command;

#[PHPUnitAttr\CoversClass(TestCommand::class)]
class TestCommandTest extends AbstractUnitCase
{
	private $command;
	private $commandTester;
	
	#[PHPUnitAttr\Before]
	public function before(): void {
		$devLoggerMock = $this->createStub('Monolog\Logger');
		$tMock = $this->createStub('Symfony\Contracts\Translation\TranslatorInterface');
		$tMock
			->method('trans')
			->willReturnArgument(0)
		;
		$progressBarSpin = [];
		
		$this->command = new TestCommand(
			devLogger: $devLoggerMock,
			t: $tMock,
			progressBarSpin: $progressBarSpin,
		);		
		$this->commandTester = new CommandTester($this->command);
	}
	
	public function testValidWhenAgree() {
		$this->commandTester->setInputs(['y']);
		$this->commandTester->execute([
			//'--grin-way-command-display-init-help' => true,
		], [
			'capture_stderr_separately' => true,
		]);
		
		$this->commandTester->assertCommandIsSuccessful();
	}
	
	public function testInvalidWhenDisagree() {
		$this->commandTester->setInputs(['n']);
		$this->commandTester->execute([
		], [
			'capture_stderr_separately' => true,
		]);
		
		$this->assertSame(Command::INVALID, $this->commandTester->getStatusCode());
	}
	
	public function testHasAffirmativeRussianTextWhenAgree() {
		$this->commandTester->setInputs(['y']);
		$this->commandTester->execute([
		], [
			'capture_stderr_separately' => true,
		]);
		
		$this->assertMatchesRegularExpression('~Продолжено~i', $this->commandTester->getDisplay());
	}
	
	public function testHasNegativeRussianTextWhenDisagree() {
		$this->commandTester->setInputs(['n']);
		$this->commandTester->execute([
		], [
			'capture_stderr_separately' => true,
		]);
		
		$this->assertMatchesRegularExpression('~Отменено~i', $this->commandTester->getDisplay());
	}
}
