<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $customer = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $comment = null;

    #[ORM\Column(length: 10)]
    private ?string $status = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\OneToMany(mappedBy: 'relatedOrder', targetEntity: OrderedItem::class)]
    public Collection $orderedItems;

    public function __construct()
    {
        $this->orderedItems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCustomer(): ?User
    {
        return $this->customer;
    }

    public function setCustomer(?User $customer): static
    {
        $this->customer = $customer;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): static
    {
        $this->comment = $comment;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

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
            $orderedItem->setRelatedOrder($this);
        }

        return $this;
    }

    public function removeOrderedItem(OrderedItem $orderedItem): static
    {
        if ($this->orderedItems->removeElement($orderedItem)) {
            // set the owning side to null (unless already changed)
            if ($orderedItem->getRelatedOrder() === $this) {
                $orderedItem->setRelatedOrder(null);
            }
        }

        return $this;
    }
}
