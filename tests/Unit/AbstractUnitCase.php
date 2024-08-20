<?php

namespace App\Tests\Unit;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Tests\InitTrait;

class AbstractUnitCase extends KernelTestCase
{
	use InitTrait;
}