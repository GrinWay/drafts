<?php

namespace App\Twig\Component\Live;

use App\Dto\User\UserDto;
use Doctrine\ORM;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

#[AsLiveComponent(
	name: 'live-search',
)]
class LiveSearchTwigComponent {

	use DefaultActionTrait;
	
	public function __construct(
		private readonly ORM\EntityManagerInterface $em,
		private readonly PropertyAccessorInterface $pa,
	) {
		$this->userDto = new UserDto();
    }
	
	#[LiveProp(writable: true, onUpdated: 'onFoodsUpdated')]
	public array $foods = [
		'pizza',
		'sushi',
	];
	
	public function onFoodsUpdated($prev): void {
		\dump('Was updated', $prev);
	}
	
	#[LiveProp(writable: ['firstName'], hydrateWith: 'hydrateUserDto', dehydrateWith: 'dehydrateUserDto')]
	public UserDto $userDto;
	
	public function hydrateUserDto(array $data): UserDto {
		return new UserDto(
			//lastName: $this->pa->getValue($data, '[lastName]'),
		);
	}
	
	public function dehydrateUserDto(?UserDto $data): array {
		return [
			//'lastName' => $this->pa->getValue($data, 'lastName'),
		];
	}

	#[LiveProp(writable: true, format: 'Y-m-d')]
	public ?\DateTime $publishOn = null;

	#[LiveProp(writable: true)]
	public string $query = '';

	#[LiveProp(writable: true)]
	public string $meal = '';

	#[LiveProp(writable: true)]
	public bool $is_markdonw_allowed = true;

	#[LiveProp(writable: true)]
	public string $text = '';

	#[LiveProp]
	public string $entity_class = '';
	
	public function getItems(): array {
		$query = \trim($this->query);
		$entityClass = $this->entity_class;
		
		if ('' === $query || '' === $entityClass) {
			return [];
		}
		
		$result = $this->em->getRepository($entityClass)->findByName($query);
		return $result;
	}
	
	public function render($item): string {
		
		if (\is_object($item)) {
			$itemString = (string) $item . ' (object found)';
		} else {
			$itemString = (string) $item;
		}
		
		return $itemString;
	}
	
}