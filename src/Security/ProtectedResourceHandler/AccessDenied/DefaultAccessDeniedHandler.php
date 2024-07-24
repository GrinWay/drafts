<?php

namespace App\Security\ProtectedResourceHandler\AccessDenied;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Type\Note\NoteType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;

class DefaultAccessDeniedHandler implements AccessDeniedHandlerInterface {
	public function __construct(
		private readonly UrlGeneratorInterface $ug,
	) {	
	}
	
	public function handle(Request $request, AccessDeniedException $accessDeniedException): ?Response
    {
		$errorMessage = $accessDeniedException->getMessage();
		$message = \sprintf(
			'В доступе отказано%s.',
			$errorMessage ? ' ('.$errorMessage.')' : '',
		);
		$request->getSession()->getFlashBag()->add(NoteType::ERROR, $message);
		return new RedirectResponse($this->ug->generate('app_home_home'));
	}
}