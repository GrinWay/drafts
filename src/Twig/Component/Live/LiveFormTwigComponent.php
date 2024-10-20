<?php

namespace App\Twig\Component\Live;

use Symfony\Component\HttpFoundation\RequestStack;
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
use Symfony\Component\Serializer\SerializerInterface;

#[AsLiveComponent(
	name: 'live-form',
)]
class LiveFormTwigComponent extends AbstractController {

	use DefaultActionTrait;
	use ComponentWithFormTrait;
	use LiveCollectionTrait;
	
	/**
     * 
     */
	#[LiveProp(hydrateWith: 'hydrateEntity', dehydrateWith: 'dehydrateEntity')]
	public object $entity;
	
	/**
     * 
     */
	#[LiveProp]
	public string $entityFormType;
	
	/**
     * 
     */
	#[LiveProp]
	public ?string $afterSubmitFormRedirectToRoute = null;
	
	/**
     * 
     */
	#[LiveProp]
	public ?string $entityClass = null;
	
	/**
     * 
     */
	#[LiveProp]
	public ?array $afterSubmitFormRedirectToRouteParams = null;
	
	/**
     * 
     */
	#[LiveProp]
	public array $formAttr = [
		'novalidate' => '',
	];
	
	/**
     * 
     */
	#[LiveProp]
	public string $updateBtnText = 'Update';
	
	/**
     * 
     */
	#[LiveProp]
	public ?string $afterSubmitFormNotice = null;
	
	/**
     * 
     */
	#[LiveProp(writable: true)]
	public ?string $value = null;
	
	/**
     * 
     */
	public function __construct(
		private readonly SerializerInterface $serializer,
		private readonly EntityManagerInterface $em,
		private readonly PropertyAccessorInterface $pa,
		private readonly RequestStack $requestStack,
	) {}
	
	/**
     * 
     */
	public function mount(
		object $entity,
		?string $entityClass = null,
		?string $afterSubmitFormRedirectToRoute = null,
		?array $afterSubmitFormRedirectToRouteParams = null,
	): void {
		$this->afterSubmitFormRedirectToRoute = $afterSubmitFormRedirectToRoute ?? $this->requestStack->getCurrentRequest()?->attributes?->get('_route') ?? throw new \Exception('Pass "afterSubmitFormRedirectToRoute" as a component parameter explicitly');
		$this->afterSubmitFormRedirectToRouteParams = $afterSubmitFormRedirectToRouteParams ?? $this->requestStack->getCurrentRequest()?->attributes?->get('_route_params') ?? [];
		$this->entityClass = $entityClass ?? \get_debug_type($entity);
		$this->entity = $entity;
	}
	
	/**
     * 
     */
	#[LiveAction]
	public function update() {
		$this->submitForm();
		
		$this->em->flush();
		
		if (null !== $this->afterSubmitFormNotice) {
			$this->addFlash(NoteType::NOTICE, $this->afterSubmitFormNotice);			
		}
		
		return $this->redirectToRoute(
			$this->afterSubmitFormRedirectToRoute,
			$this->afterSubmitFormRedirectToRouteParams,
		);
	}
	
	/**
     * ComponentWithFormTrait
     */
	protected function instantiateForm(): FormInterface
    {
        return $this->createForm(
			$this->entityFormType,
			$this->entity,
		);
    }
	
	public function hydrateEntity($data): object {
		//$object = $this->serializer->denormalize($data, $this->entityClass);
		$object = $this->em->find($this->entityClass, $this->pa->getValue($data, '[id]'));
		return $object;
	}
	
	public function dehydrateEntity($data): array {
		$array = $this->serializer->normalize($data);
		$array['cropped_image']['cropped_image'] = \base64_encode($array['cropped_image']['cropped_image']);
		//\dump($array);
		return $array;
	}
}