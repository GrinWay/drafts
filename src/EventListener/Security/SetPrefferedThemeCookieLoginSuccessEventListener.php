<?php

namespace App\EventListener\Security;

use function Symfony\component\string\u;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\SwitchUserToken;
use Symfony\Component\Translation\LocaleSwitcher;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use App\Service\StringService;
use Symfony\Component\HttpFoundation\Cookie;

#[AsEventListener]
class SetPrefferedThemeCookieLoginSuccessEventListener {
	public function __construct(
		private readonly array $appEnabledLocales,
		private $faker,
		private $enUtcCarbon,
	) {}
	
	public function __invoke(LoginSuccessEvent $e): void {
		$response = $e->getResponse();
		if (null === $response) {
			return;
		}
		
		$user = $e->getUser();
		
		//$theme = $user->getTheme();
		$theme = $this->faker->randomElement(['light', 'dark']); // pretend to get data from db
		
		$theme = Cookie::create(
			name: 'app_theme',
			value: $theme,
			expire: $this->enUtcCarbon->now()->add(1, 'year'),
			httpOnly: false,
		);
		
		$response->headers->setCookie($theme);
	}
}