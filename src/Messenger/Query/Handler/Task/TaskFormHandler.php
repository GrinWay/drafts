<?php

namespace App\Messenger\Query\Handler\Task;

use Symfony\Component\Form\FormFactoryInterface;
use App\Messenger\Query\Message\Task\TaskForm;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use GrinWay\WebApp\Type\Messenger\BusTypes;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TaskRepository;
use App\Entity\Task;
use App\Form\Type\TaskFormType;

#[AsMessageHandler(
    bus: BusTypes::QUERY_BUS,
)]
class TaskFormHandler
{
    public function __construct(
        private readonly FormFactoryInterface $formFactory,
        private readonly TaskRepository $taskRepository,
        private $enUtcCarbon,
        private $faker,
    ) {
    }

    /**
    * @return [object $task, object $form]
    */
    public function __invoke(
        TaskForm $message,
    ): array {
        $findOneBy = $message->findOneBy;
        if (null === $findOneBy) {
            $name = null;
            $deadLine = null;
            /*
            $name = $this->faker->name();
            if ($this->faker->numberBetween(0, 1)) {
                $deadLine = $this->enUtcCarbon->now()->sub($this->faker->numberBetween(0, 1000), 'days');
            } else {
                $deadLine = $this->enUtcCarbon->now()->add($this->faker->numberBetween(0, 1000), 'days');
            }
            */
            $obj = new Task(name: $name, deadLine: $deadLine);
        } else {
            $obj = $this->taskRepository->findOneBy($findOneBy);
        }
        if (null === $obj) {
            $obj = new Task();
        }
        $form = $this->formFactory->createNamed('', TaskFormType::class, $obj, [
            'form_type_nested' => [
                'name' => [
                    'label' => 'Task',
                    'required' => true,
                ],
                'dead_line' => [
                    'label' => 'Till',
                ],
            ],
            /*
            */
        ]);

        return [$obj, $form];
    }
}
