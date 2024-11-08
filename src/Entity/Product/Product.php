<?php

namespace App\Entity\Product;

use App\Entity\UserOrder;
use Symfony\Component\Validator\Constraints\GroupSequence;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use App\Contract\Doctrine\AutoMappingEnabledInterface;
use App\Entity\User;
use Symfony\Component\Validator\Constraints;
use App\Repository\ProductTypeRepository;
use App\Type\Product\ProductTypes;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping\InheritanceType;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Doctrine\ORM\Mapping\MappedSuperclass;
use App\Service\CarbonService;
use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use App\Entity\ProductPassport;
use App\Entity\AbstractEntity;
use Symfony\Component\Validator\GroupSequenceProviderInterface;

#[InheritanceType('JOINED')]
#[DiscriminatorColumn(name: 'type', type: 'string')]
#[DiscriminatorMap([
    'base_product'          => Product::class,
    ProductTypes::FURNITURE => FurnitureProduct::class,
    ProductTypes::TOY       => ToyProduct::class,
    ProductTypes::FOOD      => FoodProduct::class,
])]
#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product implements GroupSequenceProviderInterface//, AutoMappingEnabledInterface
{
    use \GrinWay\WebApp\Trait\Doctrine\UpdatedAt;
    use \GrinWay\WebApp\Trait\Doctrine\CreatedAt;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([
        'app.notifier.admin',
    ])]
    protected ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'order_items')]
    private ?UserOrder $userOrder = null;

    public function __construct(
        #[ORM\Column(length: 255)]
        #[Groups([
            'app.notifier.admin',
        ])]
        protected ?string $name = null,
        #[ORM\Column(type: Types::TEXT)]
        #[Groups([
            'app.notifier.admin',
        ])]
        protected ?string $description = null,
        #[ORM\Column(length: 255)]
        #[Groups([
            'app.notifier.admin',
        ])]
        protected ?string $price = null,
        #[ORM\Column(options: [
            'default' => false,
        ])]
        #[Groups([
            'app.notifier.admin',
        ])]
        protected bool $isPublic = false,
        #[ORM\OneToOne(inversedBy: 'product', cascade: ['persist', 'remove'], fetch: 'EAGER')]
        #[ORM\JoinColumn(nullable: true)]
        protected ?ProductPassport $passport = null,
        #[ORM\ManyToOne(inversedBy: 'products')]
        protected ?User $user = null,
    ) {
    }

    public function __clone()
    {
        \dump(__METHOD__);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPassport(): ?ProductPassport
    {
        return $this->passport;
    }

    public function setPassport(?ProductPassport $var): static
    {
        $this->passport = $var;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function isPublic(): ?bool
    {
        return $this->isPublic;
    }

    public function setPublic(bool $isPublic): static
    {
        $this->isPublic = $isPublic;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        \dump(__METHOD__);

        $this->user = $user;

        return $this;
    }

    public function setUpdatedAt(?\DateTimeImmutable $dateTime = null): static
    {
        \dump('set updated at');
        $dateTime ??= CarbonService::getNow();
        $this->updatedAt = $dateTime;
        return $this;
    }

    public function setCreatedAt(?\DateTimeImmutable $dateTime = null): static
    {
        \dump('set updated at');
        $dateTime ??= CarbonService::getNow();
        $this->createdAt = $dateTime;
        return $this;
    }

    public static function validate(mixed $obj, ExecutionContextInterface $context, mixed $payload): void
    {
        if (null === $obj->getUser()) {
            $context->buildViolation('User не может быть пустым')
                ->atPath('id')
                ->addViolation()
            ;
        }
    }

    public function isCanMakePublic(): bool
    {
        if (true === $this->isPublic()) {
            return null !== $this->getUser();
        }
        return true;
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata): void
    {
        return;
        $metadata
            ->addGetterConstraint(
                'canMakePublic',
                new Constraints\IsTrue([
                    'message' => 'Not authenticated User can\'t make the product public',
                ])
            );
    }

    public function getGroupSequence(): array|GroupSequence
    {

        if (true) {
            return ['Product', 'regex'];
        } else {
            // No groups no validations
            return [];
        }
    }

    public function getUserOrder(): ?UserOrder
    {
        return $this->userOrder;
    }

    public function setUserOrder(?UserOrder $userOrder): static
    {
        $this->userOrder = $userOrder;

        return $this;
    }
}
