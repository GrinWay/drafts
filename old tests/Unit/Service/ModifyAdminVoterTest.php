<?php

namespace App\Tests\Unit\Service;

use App\Entity\User;
use Psr\Container\ContainerInterface;
use App\Security\Voter\ModifyAdminVoter;
use Symfony\Contracts\Service\ServiceLocatorTrait;
use Symfony\Contracts\Service\ServiceProviderInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\AccessService;

class ModifyAdminVoterTest extends AbstractUnitCase {
}