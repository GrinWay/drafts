<?php

namespace App\Tests\Application;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Tests\InitTrait;
use Symfony\Component\HttpKernel\KernelInterface;
use Doctrine\ORM\EntityManagerInterface;

abstract class AbstractApplicationCase extends WebTestCase {
	use InitTrait;
}