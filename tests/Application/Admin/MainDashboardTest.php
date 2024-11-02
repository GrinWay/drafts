<?php

namespace App\Tests\Application\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use App\Controller\Admin\DashboardController;
use App\Controller\Admin\AppEasyAdminCommentCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Test\AbstractCrudTestCase;
use PHPUnit\Framework\Attributes as PHPUnitAttr;

#[PHPUnitAttr\CoversClass(DashboardController::class)]
#[PHPUnitAttr\CoversClass(AppEasyAdminCommentCrudController::class)]
class MainDashboardTest extends AbstractCrudTestCase
{
	protected function getControllerFqcn(): string
    {
        return AppEasyAdminCommentCrudController::class;
    }

    protected function getDashboardFqcn(): string
    {
        return DashboardController::class;
    }
	
	public function testRequestIsSuccessful() {
		\dump($this->getIndexColumnSelector(Action::NEW));
		
		$this->client->request("GET", $this->generateIndexUrl());
        static::assertResponseIsSuccessful();
        static::assertIndexPageEntityCount(1);
	}
}