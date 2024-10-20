<?php

namespace App\Twig\Component\Live;

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
use Symfony\UX\LiveComponent\Attribute\LiveListener;
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
	name: 'listener-live-twig-sandbox',
)]
class ListenerLiveTwigComponent extends AbstractController {

	use DefaultActionTrait;
    use ComponentToolsTrait;
	
	/**
     * 
     */
	#[LiveProp(writable: true, updateFromParent: true)]
	public string $input = 'DEFAULT DATA';
	
	/**
     * 
     */
	#[LiveProp(writable: true)]
	public bool $isCool = true;
	
	/**
     * 
     */
	#[LiveListener('app:live:custom_event')]
	public function listenCustomEvent(#[LiveArg] ?string $input = null): void {
		\dump('app:live:custom_event', __FUNCTION__);
		$this->input = $input ?? $this->input;
	}
	
	/**
     * 
     */
	#[LiveListener('some-event')]
	public function listenSomeEvent(#[LiveArg] $from = null): void {
		$from ??= '?';
		\dump('some-event-listener child from: '.$from);
	}
	
	/**
     * 
     */
	#[LiveAction]
	public function emptyLiveAction(): void {
		$this->emit('some-event', [
			'from' => 'child',
		]);
	}
}