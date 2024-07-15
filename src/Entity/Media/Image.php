<?php

namespace App\Entity\Media;

use App\Repository\ImageRepository;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation\UploadableField;

#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: ImageRepository::class)]
class Image extends Media
{
	public function __construct(
		?string $filepath = null,
	) {
		parent::__construct(
			filepath: $filepath,
		);
	}
	
	#[UploadableField(
		mapping: 'image',
		fileNameProperty: 'filepath',
		size: 'fileSize',
		mimeType: 'fileMimeType',
		originalName: 'fileOriginalName',
		dimensions: 'fileDimensions',
	)]
	protected ?File $file = null;
}
