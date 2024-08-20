<?php

namespace App\Validation\GroupProvider\Entity;

use Symfony\Component\Validator\GroupProviderInterface;
use Symfony\Component\Validator\Constraints\GroupSequence;

class ProductGroupProvider implements GroupProviderInterface {

	public function getGroups(object $objectSomeLogic): array|GroupSequence {
		if (true || $objectSomeLogic) {
			return [
				['Product'], // not Default
			];
		} else {
			// no groups no validations
			return [];
		}
	}

}