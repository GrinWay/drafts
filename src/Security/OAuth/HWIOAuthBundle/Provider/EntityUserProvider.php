<?php

namespace App\Security\OAuth\HWIOAuthBundle\Provider;

use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use HWI\Bundle\OAuthBundle\Security\Core\Exception\AccountNotLinkedException;
use App\Entity\User;
use App\Entity\GitHub;
use App\Entity\UserPassport;
use App\Repository\UserRepository;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthAwareUserProviderInterface;

class EntityUserProvider implements UserProviderInterface, OAuthAwareUserProviderInterface
{
    public function __construct(
        private $inner,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly UserRepository $userRepo,
        private readonly PropertyAccessorInterface $pa,
        private readonly EntityManagerInterface $em,
    ) {
    }

    /**
    * UserProviderInterface
    */
    public function refreshUser(UserInterface $user): UserInterface
    {
        $user = $this->inner->refreshUser($user);
        \dump(__METHOD__);

        return $user;
    }

    /**
    * UserProviderInterface
    */
    public function supportsClass(string $inputClass): bool
    {
        $supportsClass = $this->inner->supportsClass($inputClass);

        return $supportsClass;
    }

    /**
    * UserProviderInterface
    */
    public function loadUserByIdentifier(string $identifier): UserInterface
    {

        $user = $this->userRepo->getUserIfThereIsGitHubRelation([
            'gitHub.id' => $identifier,
        ]);

        if (null === $user) {
            throw new UserNotFoundException('User was not found.');
        }

        return $user;
    }

    /**
    * OAuthAwareUserProviderInterface
    */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response): ?UserInterface
    {
        $user = null;
        \dump(
            __METHOD__,
            $response->getData(),
            $response->getRealName(),
            //$response->getLastName(),
            //$response->getFirstName(),
            //$this->pa->getValue($response->getData(), '[delete]'),
        );
        /*
        \dump(
            __METHOD__,
            $response->getUsername(), // unique
            $response->getProfilePicture(),
            $response->getAccessToken(),

            $response->getNickname(),
            $response->getRealName(),
            $response->getEmail(),

            //###> NULL ###
            $response->getFirstName(),
            $response->getLastName(),

            $response->getRefreshToken(),
            $response->getTokenSecret(),
            $response->getExpiresIn(),
            //###< NULL ###

            $response->getOAuthToken(),
        );
        $user = $this->userRepo->joinedFindOneBy([
            'gitHub.id' => $response->getUsername(),
        ]);
        */

        $id = $response->getUsername();

        $user = $this->userRepo->getUserIfThereIsGitHubRelation($id);

        if (null === $user) {
            $user = $this->getNewUser($response);
        }

        return $user;
    }

    private function getNewUser(UserResponseInterface $response): User
    {
        $email = $response->getEmail();
        if (null === $email) {
            $exception = new UserNotFoundException('У в полученном ответе у пользователя нет email');
            throw $exception;
        }

        $user = new User(
            passport: new UserPassport(
                name: $response->getFirstName() ?? 'first name ' . $email,
                lastName: $response->getLastName() ?? 'last name ' . $email,
            ),
            email: $email,
            gitHub: new GitHub(
                id: $response->getUsername(),
                profilePicture: $response->getProfilePicture(),
                accessToken: $response->getAccessToken(),
            ),
        );
        $password = $this->passwordHasher->hashPassword(
            $user,
            $plainPassword = '123123',
        );
        $user->setPassword($password);
        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }
}
