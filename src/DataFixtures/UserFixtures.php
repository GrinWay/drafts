<?php

namespace App\DataFixtures;

use App\Type\Security\User\Role;
use App\Type\Product\ProductType;
use Carbon\CarbonImmutable;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use App\Entity\UserPassport;
use Symfony\Component\Messenger\Exception\StopWorkerException;

class UserFixtures extends AbstractFixtures implements DependentFixtureInterface
{
    public function __construct(
        $faker,
        #[Autowire(param: 'app.fixture.users')]
        private readonly int $count,
    ) {
        parent::__construct(
            faker: $faker,
        );
    }

    public function load(
        ObjectManager $manager
    ): void {
		$this->securityLogger()->info('NEW USER FIXTURES'.\PHP_EOL);
		
        for ($i = 0; $i < $this->count; ++$i) {
            $email = $this->faker->unique()->email;

			$passport = $this->getReference(UserPassport::class . $i);
			
			$roles = $this->faker->randomElement([
				[Role::USER],
				[Role::USER, Role::ADMIN],
			]);
			
			$user = new User(
                email: $email,
                passport: $passport,
                roles: $roles,
            );
			
			$plainPassword = $this->faker->password;
			$hashedPassword = $this->userPasswordHasher()->hashPassword($user, $plainPassword);
			$info = \sprintf('User\'s plain: "%s" and hashed password: "%s"', $plainPassword, $hashedPassword);
			$this->securityLogger()->info($info);
            
			$user->setPassword($hashedPassword);
			
			$this->addReference(self::getUserNameForProduct(false), $user);

            $manager->persist($user);
        }

        $manager->flush();
    }

    /* DependentFixtureInterface */
    public function getDependencies()
    {
        return [
            UserPassportFixture::class,
        ];
    }

    public static function getUserNameForProduct(bool $decrement = true): string
    {

        if ($decrement) {
            $usersForProductCount = self::$usersForProductCount--;
        } else {
            $usersForProductCount = ++self::$usersForProductCount;
        }

        return self::class . $usersForProductCount;
    }

    //###> HELPER ###
}
