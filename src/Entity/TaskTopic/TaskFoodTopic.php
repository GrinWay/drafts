<?php

namespace App\Entity\TaskTopic;

use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;
use App\Repository\TaskFoodTopicRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TaskFoodTopicRepository::class)]
class TaskFoodTopic extends TaskTopic
{
}
