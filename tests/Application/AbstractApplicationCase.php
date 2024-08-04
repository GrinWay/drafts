<?php

namespace App\Tests\Application;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Tests\InitTrait;
use Symfony\Component\HttpKernel\KernelInterface;
use Doctrine\ORM\EntityManagerInterface;

abstract class AbstractApplicationCase extends WebTestCase {
	protected ?EntityManagerInterface $em = null;
	
	protected function tearDown(): void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        if (null !== $this->em) {
			$this->em->close();
			$this->em = null;			
		}
    }
}