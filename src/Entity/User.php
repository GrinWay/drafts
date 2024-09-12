<?php

namespace App\Entity;

use Scheb\TwoFactorBundle\Model\TrustedDeviceInterface;
use Doctrine\DBAL\Types\Types as DBALTypes;
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
use Scheb\TwoFactorBundle\Model\Email\TwoFactorInterface as EmailTwoFactorInterface;
use Scheb\TwoFactorBundle\Model\BackupCodeInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_USER_EMAIL', fields: ['email'])]
#[ORM\UniqueConstraint(name: 'UNIQ_USER_PASSPORT', fields: ['passport'])]
#[ORM\UniqueConstraint(name: 'UNIQ_USER_API_TOKEN', fields: ['apiToken'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface, PasswordHasherAwareInterface, TotpTwoFactorInterface, GoogleTwoFactorInterface, EmailTwoFactorInterface, BackupCodeInterface, TrustedDeviceInterface//, EquatableInterface
{
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $emailAuthCode = null;

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
    #[ORM\Column(type: DBALTypes::JSON, nullable: true)]
    private ?array $backupCodes = null;
    #[ORM\Column()]
    private int $trustedVersion = 0;

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
    ) {
    }

    public function getId(): ?Ulid
    {
        return $this->id;
    }

    public function setId(?Ulid $uuid): static
    {
        $this->id = $uuid;

        return $this;
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

    public function isEqualTo(UserInterface $dbUser): bool
    {
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
    public function getPasswordHasherName(): ?string
    {
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
    public function isTotpAuthenticationEnabled(): bool
    {
        return $this->totpSecret ? true : false;
    }

    /**
     * Return the user name.
     */
    public function getTotpAuthenticationUsername(): string
    {
        return $this->getUserIdentifier();
    }

    /**
     * Return the configuration for TOTP authentication.
     */
    public function getTotpAuthenticationConfiguration(): TotpConfigurationInterface|null
    {
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

    public function isEmailAuthEnabled(): bool
    {
                          return true; // This can be a persisted field to switch email code authentication on/off
    }

    public function getEmailAuthRecipient(): string
    {
        return $this->getEmail();
    }

    public function getEmailAuthCode(): string
    {
        if (null === $this->emailAuthCode) {
            throw new \LogicException('The email authentication code was not set');
        }

                          return $this->emailAuthCode;
    }

    public function setEmailAuthCode(string $emailAuthCode): void
    {
        $this->emailAuthCode = $emailAuthCode;
    }

    public function getBackupCodes(): ?array
    {
        return $this->backupCodes;
    }

    public function setBackupCodes(?array $backupCodes): static
    {
        $this->backupCodes = $backupCodes;
        return $this;
    }

    /**
     * Check if it is a valid backup code.
     */
    public function isBackupCode(string $code): bool
    {
        return \in_array($code, $this->backupCodes);
    }

    /**
     * Invalidate a backup code
     */
    public function invalidateBackupCode(string $code): void
    {
        $key = \array_search($code, $this->backupCodes);
        if ($key !== false) {
            unset($this->backupCodes[$key]);
        }
    }

    /**
     * Add a backup code
     */
    public function addBackUpCode($backUpCode): void
    {
        if (\is_array($backUpCode)) {
            foreach ($backUpCode as $bc) {
                $this->addBackUpCode($bc);
                continue;
            }
            return;
        }

        if (\is_scalar($backUpCode)) {
            if (null === $this->backupCodes || !\in_array($backUpCode, $this->backupCodes)) {
                $this->backupCodes[] = $backUpCode;
            }
        }
    }

    public function getTrustedVersion(): int
    {
        return $this->trustedVersion;
    }

    public function setTrustedVersion(int $trustedVersion): static
    {
        $this->trustedVersion = $trustedVersion;
        return $this;
    }

    public function getTrustedTokenVersion(): int
    {
        return $this->trustedVersion;
    }

    public function invalidateTrustedTokenCookie(): static
    {
        ++$this->trustedVersion;

        return $this;
    }
}
