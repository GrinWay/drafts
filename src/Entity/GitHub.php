<?php

namespace App\Entity;

use App\Repository\GitHubRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GitHubRepository::class)]
class GitHub
{
    #[ORM\OneToOne(mappedBy: 'gitHub', cascade: ['persist'])]
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
