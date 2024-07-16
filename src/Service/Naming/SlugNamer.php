<?php

namespace App\Service\Naming;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Vich\UploaderBundle\Naming\NamerInterface;
use Vich\UploaderBundle\Mapping\PropertyMapping;
use Symfony\Component\String\Slugger\SluggerInterface;

class SlugNamer implements NamerInterface {
	public function __construct(
		private readonly SluggerInterface $slugger,
	) {}
	
	public function name(object $object, PropertyMapping $mapping): string {
		return $this->slugger->slug($object->getFile()->getClientOriginalName());
	}
}