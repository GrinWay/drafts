<?php

namespace App\Tests\Unit\Service;

use App\Tests\Unit\AbstractUnitCase;
use App\Repository\UserRepository;
use App\Entity\User;
use App\Service;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\MaxUlid;

class UserRepoServiceTest extends AbstractUnitCase {
	public function testGetUserHash(): void {
		self::bootKernel();
		$container = self::getContainer();
		
		/*
		$kernel = self::bootKernel();
		$doctrine = $kernel->getContainer()->get('doctrine');
		$em = $doctrine->getManager();
		\dd($doctrine, $em);
		*/
		
		$user = new User(
			email: 'NOT REALLY IMPORTANT',
			password: '123',
		);
		$user->setId(new MaxUlid());
		$userRepoMock = $this->createMock(UserRepository::class);
		$userRepoMock->expects(self::once())
			->method('findOneByEmail')
			->willReturn($user)
		;
		$container->set(UserRepository::class, $userRepoMock);
		
		$userRepoService = $container->get(Service\UserRepoService::class);
		$hash = $userRepoService->getHash('NOT IMPORTANT');
		
		$regexp = '~(?=.*[0-9]+)(?=.*\w+)~';
		$this->assertMatchesRegularExpression($regexp, $hash);
	}
}