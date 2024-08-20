<?php

namespace App\Entity\Media;

use App\Repository\AvatarRepository;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation\UploadableField;
use Vich\UploaderBundle\Entity\File as VichFile;
use Doctrine\DBAL\Types\Types;

#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: AvatarRepository::class)]
class Avatar extends Image
{
}
