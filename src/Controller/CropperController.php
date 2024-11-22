<?php

namespace App\Controller;

use function Symfony\Component\String\u;

use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Mime\MimeTypes;
use Doctrine\ORM\EntityManagerInterface;
use App\Type\Note\NoteType;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\UX\Cropperjs\Factory\CropperInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Media\Image;
use App\Form\Type\ImageFormType;
use Symfony\Component\Asset\Packages;
use Symfony\Component\Filesystem\Filesystem;
use App\Service;

#[Route('/cropper', name: 'app_cropper_')]
class CropperController extends AbstractController
{
	public function __construct(
		private readonly string $projectDir,
		private readonly Packages $asset,
		private readonly PropertyAccessorInterface $pa,
		private readonly Filesystem $filesystem,
	) {}
	
	#[Route('/{id<[0-9]+>?10}', name: 'index')]
    public function index(
		Image $image,
		CropperInterface $cropper,
		Request $request,
		EntityManagerInterface $em,
		#[Autowire('%app.abs_cropped_img_dir%')]
		string $absCroppedImgDir,
		#[Autowire('%app.abs_img_dir%')]
		string $absImgDir,
		#[Autowire('%app.public_img_dir%')]
		string $publicImgDir,
		#[Autowire('%app.public_cropped_img_dir%')]
		string $publicCroppedImgDir,
	): Response {
		return new Response(null, 204);
		$entity = $image;
		$absImgFilepath = \sprintf('%s/%s', $absImgDir, $entity->getFilepath());
		
		$isCroppedVersionExist = true;
		if (!\is_file($absImgFilepath)) {
			$isCroppedVersionExist = false;
		}
		
		$crop = $cropper->createCrop($absImgFilepath);
        //$crop->setCroppedMaxSize(200, 200);
		
		/**
		 * 
		 */
		$entity->setCroppedImage($crop);
		
		$entityFormType = ImageFormType::class;
		
		
		$form = $this->createForm($entityFormType, $entity, options: [
			'app_filename' => $entity->getFilepath(),
		]);
		
		$fileBeforeFormHandling = $entity->getFile();
		$form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
			//$entity = $form->getData();
			
			$croppedImage = $crop->getCroppedImage();
			//\dd($croppedImage);
			
			if ($form['isUseCrop']->getData()) {
				$croppedImgBeforeHandlingFilename = $fileBeforeFormHandling->getFilename();
				
				$absCroppedImgFilepath = \sprintf('%s/%s', $absCroppedImgDir, $croppedImgBeforeHandlingFilename);
				$this->filesystem->remove($absCroppedImgFilepath);

				if (!\is_dir($absCroppedImgDir)) {
					\mkdir($absCroppedImgDir);
				}

				/**
				 * 
				 */
				if (null === $file = $form['file']->getData()) {
					// a new file wasn't chosen
					$newImgFilename = $croppedImgBeforeHandlingFilename;
					$absCroppedImgFilepath = \sprintf('%s/%s', $absCroppedImgDir, $newImgFilename);
					\file_put_contents($absCroppedImgFilepath, $croppedImage);
					
					$entity->incrementFileVersion();					
				}
			}
			
			//\dd($entity);
			
			/*
			*/
			$em->flush();
			$this->addFlash(NoteType::NOTICE, 'updated');
			
			return $this->redirectToRoute('app_cropper_index', [
				'id' => $entity->getId(),
			]);
		}
		
		//$mimeType = (new MimeTypes())->guessMimeType($absImgFilepath);
		//$baseEncoedeFullImage = \sprintf('data:%s;base64,%s', $mimeType, \base64_encode($crop->getCroppedImage()));
		//\dd($absImgFilepath, $mimeType, $crop->getCroppedImage());
		
        return $this->render('cropper/index.html.twig', [
			'app_public_img_dir' => $publicImgDir,
			'form' => $form,
			'entity' => $entity,
			'entityFormType' => $entityFormType,
			'absImgDir' => $absImgDir,
			'is_cropped_version_exist' => $isCroppedVersionExist,
			'app_public_cropped_img_dir' => $publicCroppedImgDir,
			//'baseEncoedeFullImage' => $baseEncoedeFullImage,
		]);
    }
	
	#[Route('/show/{id<[0-9]+>?10}', name: 'show')]
    public function show(
		Image $image,
		#[Autowire('%app.public_img_dir%')]
		string $publicImgDir,
		#[Autowire('%app.public_cropped_img_dir%')]
		string $publicCroppedImgDir,
		#[Autowire('%app.abs_cropped_img_dir%')]
		string $absCroppedImgDir,
	): Response {
		$absImgFilepath = \sprintf('%s/%s', $absCroppedImgDir, $image->getFilepath());
		
		$isCroppedVersionExist = true;
		if (!\is_file($absImgFilepath)) {
			$isCroppedVersionExist = false;
		}
		
		return $this->render('cropper/show.html.twig', [
			'entity' => $image,
			'is_cropped_version_exist' => $isCroppedVersionExist,
			'app_public_img_dir' => $publicImgDir,
			'app_public_cropped_img_dir' => $publicCroppedImgDir,
		]);
	}
}
