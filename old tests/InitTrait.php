<?php

namespace App\Tests;

use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Console\Messenger\RunCommandMessage;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\StringService;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\Service\Attribute\Required;

trait InitTrait {
	
    protected function setUp(): void
    {
		parent::setUp();
    }
	
	protected function tearDown(): void
    {
        parent::tearDown();
		
    }
}