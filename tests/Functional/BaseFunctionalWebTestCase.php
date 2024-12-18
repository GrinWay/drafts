<?php

namespace App\Tests\Functional;

use App\Test\KernelBrowser\BaseKernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

abstract class BaseFunctionalWebTestCase extends WebTestCase
{
    use ResetDatabase, Factories, HasBrowser;
}
