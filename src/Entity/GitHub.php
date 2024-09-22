<?php

namespace App\Entity;

use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Annotation\Context;
use App\Repository\GitHubRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Contract\Entity\EntityInterface;

#[ORM\Entity(repositoryClass: GitHubRepository::class)]
class GitHub implements EntityInterface
{
    #[ORM\OneToOne(mappedBy: 'gitHub', cascade: ['persist'])]
	#[Context(
		context: [
			DateTimeNormalizer::FORMAT_KEY => 'Y',
		],
		groups: [
			'lala',
		],
	)]
    private ?User $user = null;

    public function __construct(
        #[ORM\Id]
        #[ORM\Column]
        private ?int $id = null,
        #[ORM\Column(nullable: true, length: 255)]
        private ?string $profilePicture = null,
        #[ORM\Column(nullable: true, length: 255)]
        private ?string $accessToken = null,
    ) {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProfilePicture(): ?string
    {
        return $this->profilePicture;
    }

    public function setProfilePicture(string $profilePicture): static
    {
        $this->profilePicture = $profilePicture;

        return $this;
    }

    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    public function setAccessToken(string $accessToken): static
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        // unset the owning side of the relation if necessary
        if ($user === null && $this->user !== null) {
            $this->user->setGitHub(null);
        }

        // set the owning side of the relation if necessary
        if ($user !== null && $user->getGitHub() !== $this) {
            $user->setGitHub($this);
        }

        $this->user = $user;

        return $this;
    }
}
