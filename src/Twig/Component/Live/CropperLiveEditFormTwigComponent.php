<?php

namespace App\Twig\Component\Live;

use Symfony\UX\Cropperjs\Factory\CropperInterface;
use Symfony\UX\Cropperjs\Model\Crop;
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
	name: 'cropper:live-edit-form',
)]
class CropperLiveEditFormTwigComponent extends AbstractController {

	use DefaultActionTrait;
	use ComponentWithFormTrait;
	use ComponentToolsTrait;
	
	/**
     * 
     */
	#[LiveProp]
	public Image $entity;
	
	/**
     * 
     */
	#[LiveProp]
	public string $entityFormType;
	
	/**
     * 
     */
	#[LiveProp]
	public string $absImgDir;
	
	/**
     * 
     */
	#[LiveProp]
	public string $app_public_img_dir;
	
	/**
     * 
     */
	#[LiveProp]
	public string $is_cropped_version_exist;
	
	/**
     * 
     */
	#[LiveProp]
	public string $app_public_cropped_img_dir;
	
	public function __construct(
		private readonly CropperInterface $cropper,
	) {}
	
	/**
     * ComponentWithFormTrait
     */
	protected function instantiateForm(): FormInterface {
		$entityFilepath = $this->entity->getFilepath();
		
		\dump($entityFilepath);
		
		$absImgFilepath = \sprintf('%s/%s', $this->absImgDir, $entityFilepath);
		//\dd($absImgFilepath);
		$crop = $this->cropper->createCrop($absImgFilepath);
		$this->entity->setCroppedImage($crop);
		
		return $this->createForm($this->entityFormType, $this->entity, options: [
			'app_filename' => $entityFilepath,
		]);
	}
}