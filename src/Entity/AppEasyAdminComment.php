<?php

namespace App\Entity;

use App\Type\Comment\CommentType;
use App\Repository\AppEasyAdminCommentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use GrinWay\WebApp\Trait\Doctrine\UpdatedAt;

#[ORM\Entity(repositoryClass: AppEasyAdminCommentRepository::class)]
class AppEasyAdminComment
{
    use UpdatedAt;
	
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $authorName = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $text = null;

	/**
     * CommentType::<>
     */
    #[ORM\Column(length: 255)]
    private ?string $type = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuthorName(): ?string
    {
        return $this->authorName;
    }

    public function setAuthorName(string $authorName): static
    {
        $this->authorName = $authorName;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): static
    {
        $this->text = $text;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }
}
