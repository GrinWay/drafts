<?php

namespace App\Security\EntryPoint;

use App\Type\Security\Voter\VoterSubject;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use App\Trait\ServiceLocator\Aware\SecurityEntryPointLoggerAware;
use App\Type\Note\NoteType;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Exception\Security\Authentication\FormLoginNeedsException;
use App\Exception\Security\Authentication\OAuthNeedsException;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Service\ServiceMethodsSubscriberTrait;
use Symfony\Contracts\Service\ServiceSubscriberInterface;

class ExceptionDependedEntryPoint implements AuthenticationEntryPointInterface, ServiceSubscriberInterface {
	
	use ServiceMethodsSubscriberTrait, SecurityEntryPointLoggerAware;
	use TargetPathTrait;	

	public function __construct(
		private readonly UrlGeneratorInterface $ug,
		private readonly RequestStack $requestStack,
		private $t,
	) {}
	
	public function start(Request $request, ?AuthenticationException $authException = null): Response {
		/*
		$debugType = \get_debug_type($authException);
		$token = $authException->getToken();
		$code = $authException->getCode();
		\dump(\sprintf(
			'Entry point for exception type: "%s"'.\PHP_EOL.'token: "%s"'.\PHP_EOL.'code: "%s"',
			$debugType,
			$token,
			$code,
		));
		*/
		
		$uri = null;
		
		if ($authException instanceof FormLoginNeedsException) {
			$uri = 'app_login';
		}

		if ($authException instanceof OAuthNeedsException) {
			$uri = 'app_o_auth_login';
		}
		
		$this->tryToAddFlash($authException);
		$this->tryTolog($authException);
		
		if (null === $uri) {
			if (null === $authException && $request->hasSession()) {
				$uri = $this->getTargetPath($request->getSession(), firewallName: 'main');				
			}
			$uri ??= 'app_login';
		}
		
		$uri = $this->ug->generate($uri);
		return new RedirectResponse($uri);
	}
	
	
	//###> HELPER ###
	private function tryTolog(
		?AuthenticationException $authException,
		string $domain = 'app.security',
	): void {
		if (null === $authException) {
			return;
		}
		$message = $this->t->trans(
			$authException->getMessage(),
			$authException->getMessageData(),
			$domain,
		);
		$this->entryPointLogger()->notice($message);
	}
	
	private function tryToAddFlash(
		?AuthenticationException $authException,
		string $noteType = NoteType::NOTICE,
		string $domain = 'app.security',
	): void {
		if (null === $authException) {
			return;
		}
		$message = $this->t->trans(
			$authException->getMessageKey(),
			$authException->getMessageData(),
			$domain,
		);
		$this->requestStack->getCurrentRequest()?->getSession()?->getFlashBag()?->add(
			$noteType,
			$message,
		);
	}
	//###< HELPER ###
	
}