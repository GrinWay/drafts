<?php

namespace App\Tests\E2E\Controller;

use App\Controller\FoundryController;
use App\Entity\Foundry;
use App\Factory\FoundryFactory;
use App\Story\FoundryStory;
use App\Tests\E2E\BasePantherTestCase;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use Zenstruck\Foundry\Attribute\WithStory;

#[CoversClass(FoundryController::class)]
#[WithStory(FoundryStory::class)]
class FoundryControllerTest extends BasePantherTestCase
{
    public function testHomePage()
    {
        $this->assertCount(10, FoundryFactory::all());

        $this->pantherBrowser()
            ->visit('/foundry')
//            ->wait(5000)//
        ;
    }
}
