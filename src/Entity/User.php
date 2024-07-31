<?php

namespace App\Entity;

use Scheb\TwoFactorBundle\Model\Totp\TotpConfigurationInterface;
use Scheb\TwoFactorBundle\Model\Totp\TotpConfiguration;
use Symfony\Component\Validator\Constraints\GroupSequence;
use Symfony\Component\Validator\GroupSequenceProviderInterface;
use App\Type\Security\User\Role;
use App\Entity\UserPassport;
use App\Repository\UserRepository;
use Symfony\Component\Uid\Ulid;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Types;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherAwareInterface;
use Scheb\TwoFactorBundle\Model\Totp\TwoFactorInterface as TotpTwoFactorInterface;
use Scheb\TwoFactorBundle\Model\Google\TwoFactorInterface as GoogleTwoFactorInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_USER_EMAIL', fields: ['email'])]
#[ORM\UniqueConstraint(name: 'UNIQ_USER_PASSPORT', fields: ['passport'])]
#[ORM\UniqueConstraint(name: 'UNIQ_USER_API_TOKEN', fields: ['apiToken'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface, PasswordHasherAwareInterface, TotpTwoFactorInterface, GoogleTwoFactorInterface//, EquatableInterface
{
    #[ORM\Id]
	#[ORM\GeneratedValue(strategy: 'CUSTOM')]
	#[ORM\CustomIdGenerator(class: 'doctrine.ulid_generator')]
    #[ORM\Column(type: Types\UlidType::NAME, unique: true)]
    private ?Ulid $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $apiToken = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $totpSecret = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $googleSecret = null;
	
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
		#[ORM\OneToOne(inversedBy: 'user', cascade: ['persist', 'remove'], fetch: 'EAGER')]
		private ?GitHub $gitHub = null,
		//private ?string $_hiddenPoly = null,
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

	/*
		public function getHiddenPoly(): ?string
		{
			return $this->_hiddenPoly;
		}
		
		public function setHiddenPoly(?string $_hiddenPoly): static
		{
			$this->_hiddenPoly = $_hiddenPoly;

			return $this;
		}
	*/

	public function getGitHub(): ?GitHub
	{
		return $this->gitHub;
	}

	public function setGitHub(?GitHub $gitHub): static
	{
		$this->gitHub = $gitHub;
  
		return $this;
	}
	
    /**
     * Return true if the user should do TOTP authentication.
     */
    public function isTotpAuthenticationEnabled(): bool {
		return $this->totpSecret ? true : false;
	}

    /**
     * Return the user name.
     */
    public function getTotpAuthenticationUsername(): string {
		return $this->getUserIdentifier();
	}

    /**
     * Return the configuration for TOTP authentication.
     */
    public function getTotpAuthenticationConfiguration(): TotpConfigurationInterface|null {
		return new TotpConfiguration(
			secret: $this->totpSecret,
			algorithm: TotpConfiguration::ALGORITHM_SHA1,
			period: 30,
			digits: 6,
		);
	}

    public function getTotpSecret(): ?string
    {
        return $this->totpSecret;
    }

    public function setTotpSecret(?string $totpSecret): static
    {
        $this->totpSecret = $totpSecret;

        return $this;
    }
	
	public function isGoogleAuthenticatorEnabled(): bool
    {
        return null !== $this->googleSecret;
    }

    public function getGoogleAuthenticatorUsername(): string
    {
        return $this->getUserIdentifier();
    }

    public function getGoogleSecret(): ?string
    {
        return $this->googleSecret;
    }

    public function setGoogleSecret(?string $googleSecret): static
    {
        $this->googleSecret = $googleSecret;

        return $this;
    }

    public function getGoogleAuthenticatorSecret(): ?string
    {
        return $this->googleSecret;
    }

    public function setGoogleAuthenticatorSecret(?string $googleSecret): void
    {
        $this->googleSecret = $googleSecret;
    }
}
