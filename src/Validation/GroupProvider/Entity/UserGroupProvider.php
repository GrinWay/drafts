<?php

namespace App\Validation\GroupProvider\Entity;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\GroupProviderInterface;
use Symfony\Component\Validator\Constraints\GroupSequence;
use App\Entity\User;
use Symfony\Component\Validator\Constraints;
use App\Form\Type\RegistrationFormType;

class UserGroupProvider implements GroupProviderInterface {

	public const DEFAULT = [
		'User',
		'register',
		'login',
	];
	
	public function getGroups(object $object): array|GroupSequence {
		
		if ($object instanceof FormInterface) {
			
			if ('app_user_registration_form' === $object->getName()) {
				return [
					'register',
				];
			}
			
			return self::DEFAULT;
		}
		
		if ($object instanceof User) {
			
			return self::DEFAULT;
		}
		
		return self::DEFAULT;
	}
	
	public function __invoke(object $object) {
		return $this->getGroups($object);
	}

}