<?php

namespace App\Security\ProtectedResourceHandler\EntryPoint;

use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Type\Security\Voter\VoterSubject;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use App\Trait\ServiceLocator\Aware\SecurityEntryPointLoggerAware;
use App\Type\Note\NoteType;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Exception\Security\Authentication\FormLoginNeedsException;
use App\Exception\Security\Authentication\OAuthNeedsException;
use App\Exception\Security\Authentication\LackOfPermissionException;
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
		throw new HttpException(Response::HTTP_UNAUTHORIZED, 'Вы не авторизованы!');
		*/
		
		if ($authException instanceof FormLoginNeedsException) {
			$uri = 'app_login';
			$this->tryToAddFlash($authException);
			$this->tryTolog($authException);
			return $this->response($authException, $uri);
		}

		if ($authException instanceof OAuthNeedsException) {
			$uri = 'app_o_auth_login';
			$this->tryToAddFlash($authException);
			$this->tryTolog($authException);
			return $this->response($authException, $uri);
		}

		if ($authException instanceof LackOfPermissionException) {
			$uri = 'app_login';
			$this->tryToAddFlash(
				$authException,
				noteType: NoteType::ERROR,
			);
			$this->tryTolog($authException);
			return $this->response($authException, $uri);
		}

		if ($authException instanceof AuthenticationException) {
			$uri = 'app_login';
			$authException = new FormLoginNeedsException();
			$this->tryToAddFlash(
				$authException,
				noteType: NoteType::WARNING,
			);
			$this->tryTolog($authException);
			return $this->response($authException, $uri);
		}
		
		return $this->response($authException);
	}
	
	//###> HELPER ###
	private function response(?AuthenticationException $authException, ?string $uri = null): Response {
		if (null === $uri) {
			if (null === $authException && $request->hasSession()) {
				$uri = $this->getTargetPath($request->getSession(), firewallName: 'main');				
			}
			$uri ??= 'app_login';
		}
		
		$uri = $this->ug->generate($uri);
		return new RedirectResponse($uri);
	}
	
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