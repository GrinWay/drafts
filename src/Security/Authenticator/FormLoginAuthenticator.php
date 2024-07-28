<?php

namespace App\Security\Authenticator;

use App\Messenger\Command\Message\SecurityAlwaysRememberMe;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\LegacyPasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\CustomCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\CredentialsInterface;
use App\Security\AuthenticationToken\FormLoginAuthenticationToken;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\PasswordUpgradeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\PreAuthenticatedUserBadge;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;
use App\Security\Badge\ModifyUserPropBadge;

class FormLoginAuthenticator extends AbstractLoginFormAuthenticator {
	
	public function __construct(
		private readonly PasswordHasherFactoryInterface $hasherFactory,
		private $get,
	) {}
	
	public function createToken(Passport $passport, string $firewallName): TokenInterface
    {
		$token = $passport->getAttribute('token');
		
        return new FormLoginAuthenticationToken(
			$passport->getUser(),
			$firewallName,
			$passport->getUser()->getRoles(),
			token: $token,
		);
    }
	
	/**
	* AbstractLoginFormAuthenticator
	*/
	protected function getLoginUrl(Request $request): string {
		return '/login';
	}
	
    public function authenticate(Request $request): Passport {
		
		$csrfTokenId = 'youWillNotHackMe';
		$userIdentifier = $request->get('_username');
		$csrfToken = $request->get('_csrf_token');
		$plainPassword = $request->get('_password');
		$isRememberMe = $request->get('_remember_me');
		$alwaysRememberMe = ($this->get)(new SecurityAlwaysRememberMe());
		
		$userBadge = new UserBadge(
			userIdentifier: $userIdentifier,
		);
		
		$credential = new PasswordCredentials($plainPassword);
		
		$badges = [
			new CsrfTokenBadge($csrfTokenId, $csrfToken),
			/*
			new ModifyUserPropBadge(
				'passport.lastName',
				static fn($origin) => \mb_strtoupper($origin),
			),
			new PreAuthenticatedUserBadge(),
			*/
		];
		
		if ($alwaysRememberMe || !empty($isRememberMe)) {
			$badges[] = (new RememberMeBadge)->enable();
		}
		
		$passport = new Passport(
			userBadge: $userBadge,
			credentials: $credential,
			badges: $badges,
		);

		$requestBasedToken = $userIdentifier.\md5($request->attributes->get('_router')).$csrfToken;
		$requestBasedToken = $this->hasherFactory->getPasswordHasher('low_cost_bcrypt')->hash($requestBasedToken);
		$passport->setAttribute('token', $requestBasedToken);
		
		return $passport;
	}
	
	public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response {
		return null;
		return new RedirectResponse('app_home_home');
	}
}