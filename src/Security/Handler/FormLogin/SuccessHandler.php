<?php

namespace App\Security\Handler\FormLogin;

use Scheb\TwoFactorBundle\Security\Authentication\Token\TwoFactorToken;
use App\Type\Note\NoteType;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SuccessHandler implements AuthenticationSuccessHandlerInterface {
	
	public function __construct(
		private readonly UrlGeneratorInterface $ug,
	) {}
	
	public function onAuthenticationSuccess(Request $request, TokenInterface $token): ?Response {
		
		$user = $token->getUser();
		
		if ($token instanceof TwoFactorToken) {
			$request->getSession()->getFlashBag()->add(NoteType::WARNING, 'Двухфакторная аутентификация');			
			return new RedirectResponse($this->ug->generate('2fa_login'));			
		} else {
			$request->getSession()->getFlashBag()->add(NoteType::NOTICE, 'Авторизация прошла успешно');			
		}
		
		return new RedirectResponse($this->ug->generate('app_home_home'));
	}
}