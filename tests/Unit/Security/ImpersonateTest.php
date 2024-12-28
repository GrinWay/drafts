<?php

namespace App\Tests\Unit\Security;

use App\Factory\UserFactory;
use App\Story\UserStory;
use PHPUnit\Framework\Attributes\CoversNothing;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\SwitchUserToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Attribute\WithStory;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

#[CoversNothing]
#[WithStory(UserStory::class)]
class ImpersonateTest extends WebTestCase
{
    use HasBrowser, Factories, ResetDatabase;

    public function testImpersonationWithHttpHeader()
    {
        $container = self::getContainer();
        $getenv = $container->get('container.getenv');

        $appUrl = $getenv('resolve:APP_URL');

        [$user, $anotherUser] = UserFactory::randomSet(2);
        assert($user instanceof UserInterface);
        assert($anotherUser instanceof UserInterface);

        $antherUserIdentifier = $anotherUser->getUserIdentifier();
        $this->browser()
            ->actingAs($user)
            ->assertAuthenticated($user)
            ->get($appUrl . '?X-Switch-User=' . $antherUserIdentifier, options: [
                'headers' => [
                    'X-Switch-User' => $antherUserIdentifier,
                ],
            ])
            ->assertSuccessful()
            ->assertAuthenticated($anotherUser);
    }
}
