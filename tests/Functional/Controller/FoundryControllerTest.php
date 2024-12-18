<?php

namespace App\Tests\Functional\Controller;

use App\Controller\FoundryController;
use App\Factory\FoundryFactory;
use App\Factory\FoundryOwnerFactory;
use App\Factory\UserFactory;
use App\Story\FoundryStory;
use App\Test\Component\VisitComponent;
use App\Tests\Functional\BaseFunctionalWebTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Zenstruck\Foundry\Attribute\WithStory;

#[CoversClass(FoundryController::class)]
#[WithStory(FoundryStory::class)]
class FoundryControllerTest extends BaseFunctionalWebTestCase
{
    public function testFoundryIndexPageShowsListOfFoundries()
    {
        $this->browser()
            ->visit('/foundry')
            ->assertSuccessful()
            ->assertSeeIn('h1', 'Foundry index')
            ->click('Home')
            ->assertSuccessful();
    }

    public function testFoundryEdit()
    {
        $this->assertGreaterThanOrEqual(1, FoundryFactory::count());
        $this->assertGreaterThanOrEqual(2, FoundryOwnerFactory::count());

        $foundry = FoundryFactory::random();
        $foundryId = $foundry->getId();

        $foundryOwnersCollection = FoundryOwnerFactory::randomSet(2);
        $anotherOwnerId = $foundryOwnersCollection[0]->getId();
        while ($foundry->getOwner()->getId() === $anotherOwnerId) {
            $anotherOwnerId = $foundryOwnersCollection[1]->getId();
        }
        $anotherOwnerId = (string)$anotherOwnerId;

        $userProxy = UserFactory::createOne([
            'roles' => [
                'ROLE_ADMIN',
            ],
        ]);

        $this->browser()
            ->use(static function (VisitComponent $visit) {
                $visit->home();
            })
            ->assertIsSuccessful()
            ->assertNotAuthenticated()
            ->actingAs($userProxy)
            ->assertAuthenticated($userProxy->getUserIdentifier())
            ->visit($editUri = \sprintf('/foundry/%s/edit', $foundryId))
            ->assertSuccessful()
            ->fillField('[title]', $title = 'It is a new label from test')
            ->fillField('Description', $desc = 'TEST DESCRIPTION FOR TESTING REASONS ONLY')
            ->selectField('Owner', $anotherOwnerId)
            ->clickAndIntercept('Update')
            ->assertRedirectedTo('/foundry', 1)
            ->visit($editUri)
            ->assertFieldEquals('[title]', $title)
            ->assertFieldEquals('Description', $desc)
            ->assertSelected('Owner', $anotherOwnerId)
            ->assertHtml()//
        ;

        $this->assertEquals($foundry->_refresh()->getOwner()->getId(), $anotherOwnerId, 'Foundry current owner has changed');
    }
}
