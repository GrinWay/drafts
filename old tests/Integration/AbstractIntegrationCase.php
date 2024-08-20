<?php

namespace App\Tests\Integration;

use Symfony\Contracts\Service\Attribute\Required;
use Symfony\Component\Console\Messenger;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Filesystem\Filesystem;
use App\Tests\InitTrait;

abstract class AbstractIntegrationCase extends KernelTestCase {
	use InitTrait;
}