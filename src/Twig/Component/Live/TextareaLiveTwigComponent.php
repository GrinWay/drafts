<?php

namespace App\Twig\Component\Live;

use Symfony\UX\LiveComponent\Attribute\LiveListener;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\Type as FormType;
use Symfony\Component\Form\FormInterface;
use App\Type\Note\NoteType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use App\Entity\Media\Image;
use App\Dto\User\UserDto;
use App\Controller\AbstractController;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\LiveCollectionTrait;
use Symfony\UX\TwigComponent\Attribute\PostMount;

#[AsLiveComponent(
	name: 'live-textarea',
)]
class TextareaLiveTwigComponent extends AbstractController {

	use DefaultActionTrait;
	
	/**
     * 
     */
	#[LiveProp]
	public string $name;
	
	/**
     * 
     */
	#[LiveProp(writable: true)]
	public ?string $value = null;
	
	public function __construct() {}
	
	/**
     * 
     */
	#[LiveAction]
	public function clear() {
		$this->value = null;
	}
}