<?php

namespace App\Entity;

use App\Repository\UserPassportRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\MappedSuperclass\Passport;

#[ORM\Entity(repositoryClass: UserPassportRepository::class)]
#[ORM\AttributeOverrides(
    [
        new ORM\AttributeOverride(
            name: 'name',
            column: new ORM\Column(
                name: 'first_name'
            )
        ),
    ]
)]
class UserPassport extends Passport
{
    use \GrinWay\WebApp\Trait\Doctrine\UpdatedAt;
    use \GrinWay\WebApp\Trait\Doctrine\CreatedAt;

    #[ORM\OneToOne(mappedBy: 'passport', cascade: ['persist'])]
    private ?User $user = null;
    public function __construct(
        ?string $name = null,
        //#[ORM\Column(type: 'key_val', length: 255)]
        #[ORM\Column(length: 255)]
        private null|array|string $lastName = null,
        #[ORM\Column(length: 30, nullable: true)]
        private ?string $timezone = null,
        #[ORM\Column(length: 10, nullable: true)]
        private ?string $lang = null,
        #[ORM\Column]
        private bool $banned = false,
        //#[ORM\Column(length: 255, unique: true)]
        //private ?string $email = null,
    ) {
        parent::__construct(
            name: $name,
        );
    }

    public function getFirstName(): ?string
    {
        return $this->name;
    }

    public function setFirstName(string $firstName): static
    {
        $this->name = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    /*
    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }
    */

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        // set the owning side of the relation if necessary
        if ($user->getPassport() !== $this) {
            $user->setPassport($this);
        }

        $this->user = $user;

        return $this;
    }

    public function getTimezone(): ?string
    {
        return $this->timezone;
    }

    public function setTimezone(?string $timezone): static
    {
        $this->timezone = $timezone;

        return $this;
    }

    public function getLang(): ?string
    {
        return $this->lang;
    }

    public function setLang(?string $lang): static
    {
        $this->lang = $lang;

        return $this;
    }

    public function isBanned(): bool
    {
        return $this->banned;
    }

    public function setBanned(bool $banned): static
    {
        $this->banned = $banned;

        return $this;
    }
}
