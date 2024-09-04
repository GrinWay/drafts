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

class CacheGetter
{
    public static function refresh(): string {
		$chars = '123456890qwertyuiopasdfghjklzxcvbnm';
		
		$chars = \str_split($chars);
		\shuffle($chars);
		
		return \implode('', $chars);
	}
}
