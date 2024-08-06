<?php

namespace App\Tests\Application\WithoutTopic;

use Doctrine\ORM\EntityManagerInterface;
use App\Repository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Tests\Application\AbstractApplicationCase;
use App\Service;

class TwoClientsTest extends AbstractApplicationCase
{
		public function test(): void {
		//$container = self::bootKernel()->getContainer();
		
		$client1 = static::createClient();
		$client1->followRedirects(true);
		
		$client2 = static::createClient();
		//$client2->insulate();
		$client2->followRedirects(true);
		
		$client1->request('GET', '/');
		
		\dd($client1->getResponse()->getStatusCode());
	}
}