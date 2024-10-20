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
	name: 'live-twig-sandbox',
)]
class LiveTwigSandboxTwigComponent extends AbstractController {

	/**
     * 
     */
	use DefaultActionTrait;
	use ComponentWithFormTrait;
	use LiveCollectionTrait;
    use ComponentToolsTrait;
	
	/**
     * 
     */
	#[LiveProp(writable: true)]
	public string $input = 'DEFAULT INPUT FROM LIVE COMPONENT';
	
	/**
     * ORM Entity
     */
	#[LiveProp]
	public Image $image;
	
	/**
     * As checkbox
     */
	#[LiveProp(writable: true)]
	public array $list = [
		'my1',
		'my3',
	];
	
	/**
     * Dto
     */
	#[LiveProp(writable: ['firstName', 'lastName'], hydrateWith: 'hydrateUserDto', dehydrateWith: 'dehydrateUserDto')]
	public ?UserDto $userDto = null;
	
	/**
     * 
     */
	#[LiveProp(writable: true)]
	public bool $parentIsCool = false;
	
	/**
     * ORM Entity Collection
     * @var Image[]
	#[LiveProp(
		writable: ['filepath'],
		useSerializerForHydration: true,
	)]
     */
	public array $entityCollection = [];
	
	/**
     * 
     */
	public function __construct(
		private readonly PropertyAccessorInterface $pa,
	) {
		$this->image ??= new Image(
			filepath: 'FROM LIVE COMPONENT',
		);
		
		$this->userDto ??= new UserDto();
		
		$this->entityCollection = [
			new Image(
				id: 71,
			),
			new Image(
				id: 72,
			),
			new Image(
				id: 73,
			),
		];
	}
	
	/**
     * 
     */
	#[LiveListener('some-event')]
	public function listenSomeEvent(#[LiveArg] $from = null): void {
		$from ??= '?';
		\dump('some-event-listener parent from: '.$from);
	}
	
	/**
     * ComponentWithFormTrait
     */
	protected function instantiateForm(): FormInterface
    {
		\dump(__FUNCTION__);
        return $this->createForm(FormType\ImageFormType::class, $this->image, []);
    }
	
	/**
     * 
     */
	#[LiveAction]
	public function emptyLiveAction(): void {
		$this->dispatchBrowserEvent('app:live:custom_event', [
			'custom-data' => true,
		]);
	}
	
	/**
     * 
     */
	#[LiveAction]
	public function reset(#[LiveArg] string $input = ''): void {
		$this->input = $input;
		$this->image?->setFilepath(null);
		$this->list = [];
		if (null !== $this->userDto) {
			$this->userDto->firstName = null;
		}			
	}
	
	/**
     * 
     */
	#[LiveAction]
	public function submitImage(
		EntityManagerInterface $em,
	) {
		//$this->formValues['filepath'] = 'OVERWRITEN';
		
		$this->submitForm();
		//$this->resetForm();
		//$this->formValues['filepath'] = 'OVERWRITEN';
		//$this->image->setFilepath('OK');
		
		$em->flush();
		
		$message = 'Обновлён image with id: "'.$this->image->getId().'"';
		$this->addFlash(NoteType::NOTICE, $message);
		
		return $this->redirectToRoute('app_live_twig_component_index');
	}
	
	/**
     * 
     */
	#[LiveAction]
	public function submitFiles(
		Request $request,
	) {
		$message = $request->files->get('one_file');
		$message = $message?->getPathname();
		$message = 'Типа файл загружен на сервер: "'.$message.'"';
		
		$this->addFlash(NoteType::NOTICE, $message);
		
		return $this->redirectToRoute(
			'app_live_twig_component_index',
			[],
		);
	}
	
	/**
     * 
     */
	public function mount(
		?string $input = null,
	) {
		$this->input = $input ?? $this->input;
	}
	
	/**
     * 
     */
	#[PostMount]
	public function postMount(
	) {
		\dump(__FUNCTION__);
	}
	
	/**
     * ComponentWithFormTrait
     */
	private function getDataModelValue(): ?string
	{
		return 'on(change)|*';
	}
	
	/**
     * (de)hydrate
     */
	public function hydrateUserDto($data) {
		return new UserDto(
			firstName: $this->pa->getValue($data, '[firstName]'),
			lastName: null,
		);
	}
	
	/**
     * (de)hydrate
     */
	public function dehydrateUserDto($data) {
		return [
			'firstName' => $this->pa->getValue($data, 'firstName'),
			'lastName' => null,
		];
	}
	
	
}