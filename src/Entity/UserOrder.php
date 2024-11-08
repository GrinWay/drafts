<?php

namespace App\Entity;

use App\Entity\Product\Product;
use App\Repository\UserOrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserOrderRepository::class)]
class UserOrder
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $status = null;

    #[ORM\OneToOne(inversedBy: 'userOrder', cascade: ['persist'])]
    private ?User $user = null;

    /**
     * @var Collection<int, Product>
     */
    #[ORM\OneToMany(targetEntity: Product::class, mappedBy: 'userOrder')]
    private Collection $order_items;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $telegramStatus = null;

    public function __construct()
    {
        $this->order_items = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status, array $context = []): static
    {
        $this->status = $status;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getOrderItems(): Collection
    {
        return $this->order_items;
    }

    public function addOrderItem(Product $orderItem): static
    {
        if (!$this->order_items->contains($orderItem)) {
            $this->order_items->add($orderItem);
            $orderItem->setUserOrder($this);
        }

        return $this;
    }

    public function removeOrderItem(Product $orderItem): static
    {
        if ($this->order_items->removeElement($orderItem)) {
            // set the owning side to null (unless already changed)
            if ($orderItem->getUserOrder() === $this) {
                $orderItem->setUserOrder(null);
            }
        }

        return $this;
    }

    public function getTelegramStatus(): ?string
    {
        return $this->telegramStatus;
    }

    public function setTelegramStatus(?string $telegramStatus): static
    {
        $this->telegramStatus = $telegramStatus;

        return $this;
    }
}
