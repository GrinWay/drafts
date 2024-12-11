<?php

namespace App\Tests\Command;

use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Tester\ApplicationTester;
use App\Command\TestCommand;
use App\Test\Command\NullConsoleOutput;
use PHPUnit\Framework\Attributes\CoversMethod;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[CoversMethod(TestCommand::class, 'execute')]
class TestCommandTest extends KernelTestCase
{
    private UrlGeneratorInterface $urlGenerator;

    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();

        $this->urlGenerator = self::getContainer()->get(UrlGeneratorInterface::class);
    }

    public function testTestCommandDisplaysExcluded(): void
    {
        \dump($this->urlGenerator->generate('app_home', referenceType: UrlGeneratorInterface::ABSOLUTE_URL));
    }
}
