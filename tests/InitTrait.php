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
	protected ?MessageBusInterface $bus = null;
	protected ?EntityManagerInterface $em = null;
	protected ?StringService $stringService = null;
	protected ?string $screenshotsRoot = null;
	
    protected function setUp(): void
    {
		parent::setUp();
    }
	
	/* Не подтягивает
	#[Required]
	public function setDependedcies(
		#[Autowire('%app.panther.screenshots%')]
		$screenshotsRoot,
		###> SERVICES
		StringService $stringService,
		MessageBusInterface $bus,
		EntityManagerInterface $em,
	): void {
		$this->screenshotsRoot = $screenshotsRoot;
		
		$this->stringService = $stringService;
		$this->bus = $bus;
		$this->em = $em;
    }
	*/
	
	protected function tearDown(): void
    {
        parent::tearDown();
		
		$this->screenshotsRoot = null;

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