<?php

namespace App\Tests;

use PHPUnit\Framework\Attributes as PHPUnitAttr;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Console\Messenger\RunCommandMessage;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\StringService;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\Service\Attribute\Required;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockFileSessionStorage;

trait InitTrait {
	
    protected function setUp(): void
    {
		parent::setUp();
		
		$container = self::getContainer();
		$requestStack = $container->get(RequestStack::class);
		
		$session = new Session(new MockFileSessionStorage());
		$request = new Request();
		$request->setSession($session);
		
		$requestStack->push($request);
		static::ensureKernelShutdown();
    }
	
	protected function tearDown(): void
    {
        parent::tearDown();
    }
}