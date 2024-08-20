<?php

namespace App\Tests\Integration\WithoutTopic;

use Doctrine\ORM\EntityManagerInterface;
use App\Repository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Tests\Integration\AbstractIntegrationCase;
use App\Service;

class WithoutTopicTest extends AbstractIntegrationCase //KernelTestCase
{
	public function testServiceForTesting(): void {
		self::bootKernel([]);
		$container = static::getContainer();
		
		$em = $container->get(EntityManagerInterface::class);
		
		/*
		$service = $container->get(Service\ServiceForTesting::class);
		
		$returnedString = $service->getPassedString('passed string');
		$this->assertEquals('passed string', $returnedString);
		
		foreach([
			['f', 'txt'],
			['f', '.txt'],
			['f', '....txt'],
		] as [$filename, $ext]) {
			$returnedString = $service->getFilenameWithExt($filename, $ext);			
			$this->assertEquals('f.txt', $returnedString);
		}
		*/
		
		$mockedStringService = $this->createMock(Service\StringService::class);
		$mockedStringService->expects(self::once())
			->method('getFilenameWithExt')
			->willReturn('\nfilename.txt')
		;
		$container->set(Service\StringService::class, $mockedStringService);
		$service = $container->get(Service\ServiceForTesting::class);
		
		$returnedString = $service->getFilenameWithExt('', '');
		$this->assertEquals('\nfilename.txt', $returnedString);
		
		$userRepo = $container->get(Repository\UserRepository::class);
		$user = $userRepo->findOneBy([]);
		$user->getPassport()->setName($name = 'Oleg');
		$em->flush();
		
		$user = $userRepo->findOneByPassport(['name' => $name]);
		$this->assertEquals('Oleg', $user->getPassport()->getName());
	}
}