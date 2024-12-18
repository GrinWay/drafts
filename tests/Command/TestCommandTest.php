<?php

namespace App\Tests\Command;

use App\Command\TestCommand;
use PHPUnit\Framework\Attributes\CoversMethod;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

#[CoversMethod(TestCommand::class, 'execute')]
class TestCommandTest extends KernelTestCase
{
    public function testTestCommandDisplaysExcluded(): void
    {
        self::bootKernel();
    }
}
