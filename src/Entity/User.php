<?php

namespace App\Entity;

use App\Type\Security\User\Role;
use App\Entity\UserPassport;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Uid\Ulid;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Types;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherAwareInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_USER_EMAIL', fields: ['email'])]
#[ORM\UniqueConstraint(name: 'UNIQ_USER_PASSPORT', fields: ['passport'])]
#[ORM\UniqueConstraint(name: 'UNIQ_USER_API_TOKEN', fields: ['apiToken'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface, PasswordHasherAwareInterface//, EquatableInterface
{
    #[ORM\Id]
	#[ORM\GeneratedValue(strategy: 'CUSTOM')]
	#[ORM\CustomIdGenerator(class: 'doctrine.ulid_generator')]
    #[ORM\Column(type: Types\UlidType::NAME, unique: true)]
    private ?Ulid $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $apiToken = null;

	/**
	 * @var array $roles list<string> The user roles
	 * @var ?string $password string The hashed password
	 */
	public function __construct(
		#[ORM\Column(length: 255)]
		private ?string $email = null,
		#[ORM\OneToOne(inversedBy: 'user', cascade: ['persist', 'remove'], fetch: 'EAGER')]
		#[ORM\JoinColumn(nullable: false)]
		private ?UserPassport $passport = null,
		#[ORM\Column]
		private array $roles = [],
		#[ORM\Column]
		private ?string $password = null,
		#[ORM\Column()]
		private bool $switchUserAble = false,
	) {}

    public function getId(): ?Ulid
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = Role::USER;

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getPassport(): ?UserPassport
    {
        return $this->passport;
    }

    public function setPassport(UserPassport $passport): static
    {
        $this->passport = $passport;

        return $this;
    }
	
	public function isEqualTo(UserInterface $dbUser): bool {
		//\dd($dbUser, $this, $dbUser === $this);
		
		return true
			//&& $dbUser->getUserIdentifier() === $this->getUserIdentifier()
			//&& $dbUser->getId() === $this->getId()
			//&& $dbUser->getRoles() === $this->getRoles()
			//&& $dbUser->isSwitchUserAble() === $this->isSwitchUserAble()
		;
	}
	
	/**
	* PasswordHasherAwareInterface
	*/
	public function getPasswordHasherName(): ?string {
		$hasher = null;
		
		if (\in_array('ROLE_ADMIN', $this->getRoles())) {
			$hasher = 'admin_hasher';
		}
		
		return $hasher;
	}

    public function isSwitchUserAble(): bool
    {
        return $this->switchUserAble;
    }

    public function setSwitchUserAble(bool $switchUserAble): static
    {
        $this->switchUserAble = $switchUserAble;

        return $this;
    }

    public function getApiToken(): ?string
    {
        return $this->apiToken;
    }

    public function setApiToken(?string $apiToken): static
    {
        $this->apiToken = $apiToken;

        return $this;
    }
}
