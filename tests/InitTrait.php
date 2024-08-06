<?php

namespace App\Tests;

use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Console\Messenger\RunCommandMessage;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\StringService;

trait InitTrait {
	protected ?MessageBusInterface $bus = null;
	protected ?EntityManagerInterface $em = null;
	protected ?StringService $stringService = null;
	protected string $screenshotsRoot;
	
    protected function setUp(): void
    {
		parent::setUp();
		return;
		$container = static::getContainer();
		
		###> PARAMETERS ###
		$this->screenshotsRoot = $container->getParameter('app.panther.screenshots');
		###< PARAMETERS ###
		
		###> SERVICES ###
		$this->stringService = $container->get(StringService::class);
		$this->bus = $container->get(MessageBusInterface::class);
		$this->em = $container->get(EntityManagerInterface::class);
		###< SERVICES ###
    }
	
	protected function tearDown(): void
    {
        parent::tearDown();
		
		$this->screenshotsRoot = '';

        if (null !== $this->stringService) {
			$this->stringService = null;
		}
		
        if (null !== $this->em) {
			$this->em->close();
			$this->em = null;		
		}
		
        if (null !== $this->bus) {
			$this->bus = null;		
		}
    }
}