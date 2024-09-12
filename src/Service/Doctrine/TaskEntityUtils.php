<?php

namespace App\Service\Doctrine;

use Doctrine\Common\DataFixtures\Purger\PurgerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\DataFixtures\Purger\ORMPurgerInterface;
use App\Entity\Task;
use Symfony\Component\String\Slugger\SluggerInterface;

class TaskEntityUtils
{
    public static function getSlug(SluggerInterface $slugger, Task $obj): string
    {
        $slug = ($obj->getName() ?? 'NULL_NAME' . \rand(0, 500)) . '-' . ($obj->getDeadLine()?->format(\DateTime::COOKIE) ?? 'NULL_DATE' . \rand(0, 1000));
        return $slugger->slug($slug);
    }
}
