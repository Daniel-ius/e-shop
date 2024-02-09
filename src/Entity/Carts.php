<?php

namespace App\Entity;

use App\Repository\CartsRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CartsRepository::class)]
class Carts
{
    const STATUS_CART='cart';
    const STATUS_CHECKOUT = 'checkout';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(targetEntity: cartitems::class, mappedBy: 'carts')]
    private Collection $items;

    #[ORM\Column(type: 'datetime')]
    private DateTimeInterface $createdAt;
    #[ORM\Column(type: 'datetime')]
    private ?DateTimeInterface $updatedAt;
    #[ORM\Column(type: 'string', length: 255)]
    private string $status = self::STATUS_CART;
    #[ORM\Column]
    private ?float $total = null;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, cartitems>
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(cartitems $item): static
    {
        if (!$this->items->contains($item)) {
            $this->items->add($item);
            $item->setCarts($this);
        }

        return $this;
    }
    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }
    public function removeItem(cartitems $item): static
    {
        if ($this->items->removeElement($item)) {
            // set the owning side to null (unless already changed)
            if ($item->getCarts() === $this) {
                $item->setCarts(null);
            }
        }

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
    public function setTotal(): static
    {
        foreach ($this->getItems() as $item) {
            $this->total += $item->getTotalPrice();
        };

        return $this;
    }
}
