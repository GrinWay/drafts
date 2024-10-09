<?php

namespace App\Entity;

use App\Repository\MachineRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: MachineRepository::class)]
#[Broadcast(
	template: 'machine/Machine.stream.html.twig',
	topics: [
		'app_machine',
		'@="machine_" ~ entity.getId()',
		
		//'app_{{ currentUserId }}_machine',
		//'@="app_{{ currentUserId }}_machine_" ~ entity.getId()',
	],
	private: true,
	/*
	transports: [
		'async',
	],
	*/
)]
class Machine
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $year = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): static
    {
        $this->year = $year;

        return $this;
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
}
