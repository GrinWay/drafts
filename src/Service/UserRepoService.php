<?php

namespace App\Service;

use App\Repository\UserRepository;

class UserRepoService
{
	public function __construct(
		private readonly UserRepository $userRepo,
	) {}
	
	public function getHash(string $email): string {
		$user = $this->userRepo->findOneByEmail($email);
		if (null === $user) {
			throw new \Exception(\sprintf('User not found by email: "%s"', $email));
		}
		return \md5($user->getId().$user->getEmail().$user->getPassword());
	}
}
