<?php

namespace App\Entity;

use App\Entity\TaskTopic\TaskTopic;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;
use App\Repository\TaskRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task
{
    public $year;
    public $month;
    public $day;
    public $hour;
    public $minute;
    public $second;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[ORM\Column(type: 'uuid', unique: true)]
    private ?Uuid $id = null;

    public function __construct(
        //#[Constraints\NotBlank]
        #[ORM\Column(length: 255, nullable: true)]
        /**
         * @guess string $name
         */
        private ?string $name = null,
        #[ORM\Column(type: 'datetime_immutable', nullable: true)]
        private ?\DateTimeInterface $deadLine = null,
        #[ORM\Column(length: 255, unique: true)]
        private ?string $slug = null,
        #[ORM\ManyToOne(inversedBy: 'tasks', cascade: ['persist', 'remove'])]
        #[ORM\JoinColumn(nullable: false)]
        private ?TaskTopic $topic = null,
    ) {
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDeadLine(): ?\DateTimeInterface
    {
        return $this->deadLine;
    }

    public function setDeadLine(?\DateTimeInterface $deadLine): static
    {
        $this->deadLine = $deadLine;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getTopic(): ?TaskTopic
    {
        return $this->topic;
    }

    public function setTopic(?TaskTopic $topic): static
    {
        $this->topic = $topic;

        return $this;
    }
}
