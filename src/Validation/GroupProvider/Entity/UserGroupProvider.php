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
		
		$message = \sprintf(
			'Incorrect usage. Only for "%s" and "%s" type.',
			User::class,
			FormInterface::class,
		);
		throw new \LogicException($message);
		
		return [];
	}
	
	// так не надо! validation_groups => $this используй аттрибут GroupSequenceProvider
	public function __invoke(object $object) {
		return $this->getGroups($object);
	}

}