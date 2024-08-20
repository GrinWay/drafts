<?php

namespace App\Service;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\NullToken;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\SecurityBundle\Security;
use App\Exception\Security\AccessDenied\RoleNotGrantedAccessDeniedException;
use App\Exception\Security\Authentication\FormLoginNeedsException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

//TODO: AccessService
/** Usage:
* 
* $this->accessService->denyAccessUnlessGranted(
*     'IS_AUTHENTICATED_FULLY',
*     onRoleNotGranted: static fn($role) => throw new FormLoginNeedsException(),
* );
*/
class AccessService
{
    public function __construct(
        private readonly AuthorizationCheckerInterface $authChecker,
        protected readonly Security $security,
    ) {
    }
	
	//###> API ###
	
	/**
	* By default if null === user runs EntryPoint with AuthenticationException
	* By default if !isGranted(ROLE) runs AccessDeniedHandler with AccessDeniedException
	*/
    public function denyAccessUnlessGranted(string $role, AuthenticationException|AccessDeniedException|callable|null $onRoleNotGranted = null): void {
		$token = $this->security->getToken() ?? new NullToken();
		$user = $this->security->getUser();
		
		//###> AuthenticationException only for EntryPointHandler
		if ($token instanceof NullToken || null === $user) {
			$onRoleNotGranted ??= new FormLoginNeedsException('User is not authorized.');
			$this->process($onRoleNotGranted, $role);
		}

		//###> AccessDeniedException only for AccessDeniedHandler
		if (!$this->authChecker->isGranted($role)) {
			$onRoleNotGranted ??= throw new RoleNotGrantedAccessDeniedException($role);
			$this->process($onRoleNotGranted, $role);
		}
	}
	//###< API ###
	
	private function process(AuthenticationException|AccessDeniedException|callable $callbackOrException, string $role): void {
		if (!\is_callable($callbackOrException)) {
			throw $callbackOrException;
		}
		
		$message = \sprintf(
			'Incorrect Usage: You had to throw the "%s" or "%s" in your callback.',
			AuthenticationException::class,
			AccessDeniedException::class,
		);
		$incorrectUsageException = new \Exception($message);
		try {
			$callbackOrException($role);
		} catch (AuthenticationException $e) {
			throw $e;
		} catch (AccessDeniedException $e) {
			throw $e;
		} catch (\Exception $e) {
			throw $incorrectUsageException;
		}
		throw $incorrectUsageException;
	}
}
