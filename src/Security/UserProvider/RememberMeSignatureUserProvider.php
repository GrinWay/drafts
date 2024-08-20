<?php

namespace App\Security\UserProvider;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\Mapping\ClassMetadata;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;
use Doctrine\Persistence\Proxy;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use App\Service\ConfigService;

// TODO: RememberMeSignatureUserProvider
class RememberMeSignatureUserProvider implements UserProviderInterface
{
	public function __construct(
		private $inner,
		private readonly UserRepository $userRepo,
		private readonly ConfigService $configService,
		private $pa,
		private $securityLogger,
	) {
	}
	
    public function loadUserByIdentifier(string $identifier): UserInterface
    {
		return $this->inner->loadUserByIdentifier($identifier);
    }

    public function refreshUser(UserInterface $sessionDeserializedUser): UserInterface
    {
		$this->invalidateUserAuthorizationIfDoctrineWasChangedByRememberMeSignature($sessionDeserializedUser);
		
		return $this->inner->refreshUser($sessionDeserializedUser);
    }

    public function supportsClass(string $class): bool
    {
		return $this->inner->supportsClass($class);
    }

    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
		$this->inner->upgradePassword($user, $newHashedPassword);
	}
	
	/**
	* @throws UserNotFoundException (If remember me signature properties from db was changed)
	*/
	private function invalidateUserAuthorizationIfDoctrineWasChangedByRememberMeSignature(UserInterface $sessionDeserializedUser): void {
		$doctrineUser = $this->userRepo->find($sessionDeserializedUser->getId());
		
		if (null == $doctrineUser) {
			throw new UserNotFoundException('User was not downloaded from database.');
		}
		
		$rememberMePropertyAccesssorStrings = $this->configService->getPackageValue(
			packName:					'security.yaml',
			propertyAccessString:		'[security][firewalls][main][remember_me][signature_properties]',
			packRelPath:				'config/packages',
		);
		
		if (empty($rememberMePropertyAccesssorStrings)) {
			return;
		}
		
		foreach($rememberMePropertyAccesssorStrings as $paString) {
			$sessionValue = $this->pa->getValue($sessionDeserializedUser, $paString);
			$doctrineValue = $this->pa->getValue($doctrineUser, $paString);

			if ($sessionValue != $doctrineValue) {
				$message = \sprintf(
					'Property "%1$s" of "%2$s" are not equal. session: "%3$s" != doctrine: "%4$s".',
					$paString,
					User::class,
					$sessionValue,
					$doctrineValue,
				);
				throw new UserNotFoundException($message);
				//$this->securityLogger->notice($message);
			}
		}
	}
}