<?php

namespace App\Entity\TaskTopic;

use App\Entity\Task;
use App\Doctrine\Collection\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\MappedSuperclass;
use App\Repository\TaskTopicRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Type\Task\TaskTopicTypes;
use App\Contract\TypesAwareInterface;

#[ORM\InheritanceType('JOINED')]
#[ORM\DiscriminatorColumn(name: 'type', type: 'string')]
#[ORM\DiscriminatorMap([
    TaskTopicTypes::DEFAULT => TaskTopic::class,
    TaskTopicTypes::FOOD	=> TaskFoodTopic::class,
])]
#[ORM\Entity(repositoryClass: TaskTopicRepository::class)]
class TaskTopic implements TypesAwareInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    protected ?int $id = null;

    /**
     * @var Collection<int, Task>
     */
    #[ORM\OneToMany(targetEntity: Task::class, mappedBy: 'topic', cascade: ['persist', 'remove'])]
    protected Collection $tasks;

	public function __construct(
		#[ORM\Column(length: 255)]
		protected ?string $name = null,
	) {
		$this->tasks = new ArrayCollection();
	}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Task>
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function addTask(Task $task): static
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks->add($task);
            $task->setTopic($this);
        }

        return $this;
    }

    public function removeTask(Task $task): static
    {
        if ($this->tasks->removeElement($task)) {
            // set the owning side to null (unless already changed)
            if ($task->getTopic() === $this) {
                $task->setTopic(null);
            }
        }

        return $this;
    }
	
	public function getTypesClass(): string {
		return TaskTopicTypes::class;
	}
}
