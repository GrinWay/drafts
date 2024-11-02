<?php

namespace App\Entity;

use App\Type\Comment\CommentType;
use App\Repository\AppEasyAdminCommentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use GrinWay\WebApp\Trait\Doctrine\UpdatedAt;
use Doctrine\ORM\Mapping\UniqueConstraint;

#[ORM\Entity(repositoryClass: AppEasyAdminCommentRepository::class)]
#[UniqueConstraint('COMMENT_SLUG_IDX', fields: ['slug'])]
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
     * @var string $type (one of CommentType::ALL)
     */
    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column(nullable: true)]
    private ?\DateInterval $invitationInterval = null;

    #[ORM\Column(type: Types::FLOAT)]
    private float|int $numberFromZeroToHundred = 0.01;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $avatar = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

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

    public function getInvitationInterval(): ?\DateInterval
    {
        return $this->invitationInterval;
    }

    public function setInvitationInterval(?\DateInterval $invitationInterval): static
    {
        $this->invitationInterval = $invitationInterval;

        return $this;
    }

    public function getNumberFromZeroToHundred(): float
    {
        return $this->numberFromZeroToHundred;
    }

    public function setNumberFromZeroToHundred(float|int $numberFromZeroToHundred): static
    {
        $this->numberFromZeroToHundred = $numberFromZeroToHundred;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): static
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }
}
