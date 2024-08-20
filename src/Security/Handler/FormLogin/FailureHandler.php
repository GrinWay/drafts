<?php

namespace App\Security\Handler\FormLogin;

use App\Type\Note\NoteType;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class FailureHandler implements AuthenticationFailureHandlerInterface {
	
	public function __construct(
		private readonly UrlGeneratorInterface $ug,
		private $t,
	) {}
	
	public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response {
		$message = \sprintf(
			'Авторизация провалилась (%s)',
			$this->t->trans(
				$exception->getMessageKey(),
				$exception->getMessageData(),
				'security'
			),
		);
		$request->getSession()->getFlashBag()->add(NoteType::ERROR, $message);
		return new RedirectResponse($this->ug->generate('app_login'));
	}
}