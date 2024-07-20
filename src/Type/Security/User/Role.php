<?php

namespace App\Type\Security\User;

class Role {
	public const USER       = 'ROLE_USER';
	public const ADMIN      = 'ROLE_ADMIN';
	public const OWNER      = 'ROLE_OWNER';
	
	public const ALL = [
		'USER'              => self::USER,
		'ADMIN'             => self::ADMIN,
		'OWNER'             => self::OWNER,
	];
}