<?php

namespace App\Validation\GroupProvider\Entity;

use Symfony\Component\Validator\GroupProviderInterface;
use Symfony\Component\Validator\Constraints\GroupSequence;
use Symfony\Component\Validator\Constraints;

#[Constraints\GroupSequenceProvider]
class UserGroupProvider implements GroupProviderInterface {

	public function getGroups(object $object): array|GroupSequence {
		//TODO: current не знаю почему не вызывается GroupProvider
		// https://symfony.com/doc/current/validation/sequence_provider.html
		\dd($object);
		return [
			'User',
			'register',
			'login',
		];
	}

}