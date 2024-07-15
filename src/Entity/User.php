<?php

namespace App\Entity;

use App\Entity\UserPassport;
use App\Entity\Product\Product;
use Symfony\Component\Validator\Constraints;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;
use App\Service\CarbonService;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\Ignore;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[Groups([
        'app.notifier.admin',
    ])]
    private ?Uuid $id = null;

    /**
     * This is the doctrine collection.
     * That's no a usual array.
     *
     * @var Collection<int|string, Product>
     */
    #[ORM\OneToMany(targetEntity: Product::class, mappedBy: 'user', orphanRemoval: false)]
    private Collection $products;

    /**
    * @param ?UserPassport $passport
    */
    public function __construct(
        #[ORM\OneToOne(inversedBy: 'user', cascade: ['persist', 'remove'])]
        #[ORM\JoinColumn(nullable: false)]
        private ?UserPassport $passport = null
    ) {
        $this->products = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getPassport(): ?UserPassport
    {
        return $this->passport;
    }

    public function setPassport(UserPassport $passport): static
    {
        $this->passport = $passport;

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
		\dump('get products');
		
        return $this->products;
    }

    public function addProduct(Product $product): static
    {
		\dump('add product', $product->getId());
        if (!$this->products->contains($product)) {
            $this->products->add($product);
            $product->setUser($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): static
    {
		\dump('remove product', $product->getId());
		\dump('remove method user', $this);
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getUser() === $this) {
                $product->setUser(null);
            }
        }

        return $this;
    }
}
