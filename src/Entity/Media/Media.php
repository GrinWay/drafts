<?php

namespace App\Entity\Media;

use function Symfony\Component\String\u;

use Carbon\Carbon;
use App\Type\Media\MediaType;
use App\Repository\MediaRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Vich\UploaderBundle\Mapping\Annotation\UploadableField;
use GrinWay\WebApp\Trait\Doctrine\CreatedAt;
use GrinWay\WebApp\Trait\Doctrine\UpdatedAt;
use Vich\UploaderBundle\Entity\File as VichFile;
use App\Validation\GroupProvider\Entity\MediaGroupProvider;

#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: MediaRepository::class)]
#[ORM\InheritanceType('JOINED')]
#[ORM\DiscriminatorColumn(name: 'type', type: 'string')]
#[ORM\DiscriminatorMap([
    'base_media'            => Media::class,
    MediaType::IMAGE        => Image::class,
    MediaType::AVATAR       => Avatar::class,
])]
//#[Constraints\GroupSequenceProvider(MediaGroupProvider::class)]
class Media
{
    use CreatedAt;
    use UpdatedAt;

    #[UploadableField(
        mapping: 'media',
        fileNameProperty: 'filepath',
        originalName: 'fileOriginalName',
    )]
    protected ?File $file = null;

    //#[ORM\Embedded(class: VichFile::class)]
    protected ?VichFile $vichFile = null;

    #[ORM\Column(length: 60, nullable: true)]
    protected ?string $fileToken = null;

    #[ORM\Column(type: Types::SIMPLE_ARRAY, nullable: true)]
    protected ?array $keyFrames = null;

    #[ORM\Column]
    private int $fileVersion = 0;

    public function __construct(
		#[ORM\Id]
		#[ORM\GeneratedValue]
		#[ORM\Column]
		protected ?int $id = null,
        #[ORM\Column(length: 255)]
        protected ?string $filepath = null,
        #[ORM\Column(length: 255)]
        protected ?string $fileOriginalName = null,
    ) {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVichFile(): ?VichFile
    {
        return $this->vichFile;
    }

    public function setVichFile(?VichFile $vichFile): static
    {
        $this->vichFile = $vichFile;

        return $this;
    }

    public function getFilepath(): ?string
    {
        return $this->filepath;
    }

    public function setFilepath(?string $filepath): static
    {
        $this->filepath = $filepath;

        return $this;
    }

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function setFile(?File $file = null): static
    {
        $this->file = $file;

        if (null !== $file) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updateFileToken();
        }

        return $this;
    }



    public function setFileOriginalName(?string $fileOriginalName): static
    {
                $this->fileOriginalName = $fileOriginalName;
                return $this;
    }

	/*
    public function setFileMimeType(?string $fileMimeType): static
    {
                $this->fileMimeType = $fileMimeType;
                return $this;
    }
    public function setFileSize(?int $fileSize): static
    {
                $this->fileSize = $fileSize;
                return $this;
    }
    public function getFileSize(): ?int
    {
                return $this->fileSize;
    }
    public function getFileMimeType(): ?string
    {
                return $this->fileMimeType;
    }
	*/


    public function getFileOriginalName(): ?string
    {
                return $this->fileOriginalName;
    }

    public function getMd5(): string
    {
                return md5($this->id);
    }

    public function getFileToken(): ?string
    {
        return $this->fileToken;
    }

    public function setFileToken(?string $fileToken): static
    {
        $this->fileToken = $fileToken;

        return $this;
    }

    public function updateFileToken(): static
    {
        $this->fileToken = \substr(\md5($this->filepath.\time()), 0, 60);

        return $this;
    }

    public function getKeyFrames(): ?array
    {
        return $this->keyFrames;
    }

    public function setKeyFrames(?array $keyFrames): static
    {
        $this->keyFrames = $keyFrames;

        return $this;
    }

    public function getFileVersion(): int
    {
        return $this->fileVersion;
    }

    public function incrementFileVersion(): static
    {
        ++$this->fileVersion;
		return $this;
    }

    public function setFileVersion(int $fileVersion): static
    {
        $this->fileVersion = $fileVersion;

        return $this;
    }
}
