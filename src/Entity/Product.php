<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column(length: 100)]
    private ?string $category = null;

    #[ORM\Column(length: 10)]
    private ?string $price = null;

    #[ORM\Column(length: 20)]
    private ?string $unit = null;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: OrderedItem::class, orphanRemoval: true)]
    private Collection $orderedItems;

    public function __construct()
    {
        $this->orderedItems = new ArrayCollection();
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

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): static
    {
        $this->category = $category;

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

    public function getUnit(): ?string
    {
        return $this->unit;
    }

    public function setUnit(string $unit): static
    {
        $this->unit = $unit;

        return $this;
    }

    /**
     * @return Collection<int, OrderedItem>
     */
    public function getOrderedItems(): Collection
    {
        return $this->orderedItems;
    }

    public function addOrderedItem(OrderedItem $orderedItem): static
    {
        if (!$this->orderedItems->contains($orderedItem)) {
            $this->orderedItems->add($orderedItem);
            $orderedItem->setProduct($this);
        }

        return $this;
    }

    public function removeOrderedItem(OrderedItem $orderedItem): static
    {
        if ($this->orderedItems->removeElement($orderedItem)) {
            // set the owning side to null (unless already changed)
            if ($orderedItem->getProduct() === $this) {
                $orderedItem->setProduct(null);
            }
        }

        return $this;
    }
}
