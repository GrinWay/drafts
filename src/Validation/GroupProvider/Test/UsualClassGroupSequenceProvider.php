<?php

namespace App\Validation\GroupProvider\Test;

use Symfony\Component\Validator\GroupProviderInterface;
use Symfony\Component\Validator\Constraints\GroupSequence;

class UsualClassGroupSequenceProvider implements GroupProviderInterface
{
	public function getGroups(object $object): array|GroupSequence {
		$groups = [
			['UsualClass', 'updating'],
			'init',
		];
		
		if (false) {
			$groups[0] = '';
		}
		
		return $groups;
	}
}