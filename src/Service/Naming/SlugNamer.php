<?php

namespace App\Service\Naming;

use Vich\UploaderBundle\Naming\NamerInterface;
use Vich\UploaderBundle\Mapping\PropertyMapping;

class SlugNamer implements NamerInterface {
	public function name(object $object, PropertyMapping $mapping): string {
		return $object->getFile()->getClientOriginalName();
	}
}