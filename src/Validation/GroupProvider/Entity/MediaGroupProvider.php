<?php

namespace App\Validation\GroupProvider\Entity;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\GroupProviderInterface;
use Symfony\Component\Validator\Constraints\GroupSequence;
use App\Entity\User;
use Symfony\Component\Validator\Constraints;
use App\Form\Type\RegistrationFormType;

class MediaGroupProvider implements GroupProviderInterface {

	public const DEFAULT = [
		'Media',
		'Image',
		'Avatar',
	];
	
	public function getGroups(object $object): array|GroupSequence {
		
		return [
			[
				'_1',
				'_2',
			],
			'_3',
			'_4',
		];
	}
}