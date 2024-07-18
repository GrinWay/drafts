<?php

namespace App\Entity\Media;

use App\Doctrine\DTO\UserDto;
use App\Repository\ImageRepository;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation\UploadableField;
use Vich\UploaderBundle\Entity\File as VichFile;
use Doctrine\DBAL\Types\Types;

#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: ImageRepository::class)]
class Image extends Media
{
	public function __construct(
		//?VichFile $vichFile = null,
		?string $filepath = null,
		?string $fileOriginalName = null,
	) {
		parent::__construct(
			//vichFile: $vichFile,
			filepath: $filepath,
			fileOriginalName: $fileOriginalName,
		);
	}
	
	#[UploadableField(
		mapping: 'image',
		fileNameProperty: 'filepath',
		
		originalName: 'fileOriginalName',
		dimensions: 'fileDimensions',
	)]
	protected ?File $file = null;
	
	#[ORM\Column(type: Types::SIMPLE_ARRAY, nullable: true)]
	protected ?array $fileDimensions = null;
	
	public function setFileDimensions(?array $fileDimensions): static {
		$this->fileDimensions = $fileDimensions;
		return $this;
	}
	
	public function getFileDimensions(): ?array {
		return $this->fileDimensions;
	}
	
	
	protected ?UserDto $userDto = null;
	
	public function setUserDto(?UserDto $userDto): static {
		\dump(__METHOD__);
		$this->userDto = $userDto;
		return $this;
	}
	
	public function getUserDto(): ?UserDto {
		\dump(__METHOD__);
		return $this->userDto;
	}
}
