<?php

namespace App\Tests;

use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Console\Messenger\RunCommandMessage;
use Doctrine\ORM\EntityManagerInterface;

trait InitTrait {
	protected ?MessageBusInterface $bus = null;
	protected ?EntityManagerInterface $em = null;
	
    protected function setUp(): void
    {
		parent::setUp();
		$container = static::getContainer();
		
		$this->bus = $container->get(MessageBusInterface::class);
		$this->em = $container->get(EntityManagerInterface::class);
    }
	
	protected function tearDown(): void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        if (null !== $this->em) {
			$this->em->close();
			$this->em = null;		
		}
		
        if (null !== $this->bus) {
			$this->bus = null;		
		}
    }
}