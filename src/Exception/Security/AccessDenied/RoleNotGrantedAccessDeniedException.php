<?php

namespace App\Exception\Security\AccessDenied;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class RoleNotGrantedAccessDeniedException extends AccessDeniedException {
	public function __construct(
		string $role,
		int $code = 0,
		?\Throwable $previous = null,
	) {
		$message = \sprintf(
			'You don\'t possess the role: "%s".',
			$role,
		);
		
		parent::__construct(
			message: $message,
			code: $code,
			previous: $previous,
		);
	}
}