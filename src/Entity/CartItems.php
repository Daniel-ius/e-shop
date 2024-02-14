<?php

namespace App\Entity;

use App\Repository\CartItemsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CartItemsRepository::class)]
class CartItems
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'integer',options: ['default'=>1])]
    private ?int $quantity = 1;

    #[ORM\Column(type: 'float', precision: 2, scale: 2)]
    private float $totalPrice = 0.0;
    #[ORM\ManyToOne]
    private ?Product $item = null;

    #[ORM\ManyToOne(inversedBy: 'items')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Cart $carts = null;
    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(?int $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function getTotalPrice(): float
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(): void
    {
        $this->totalPrice = $this->getItem()->getPrice() * $this->getQuantity();
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getItem(): ?Product
    {
        return $this->item;
    }

    public function setItem(?Product $item): static
    {
        $this->item = $item;

        return $this;
    }

    public function getCarts(): ?Cart
    {
        return $this->carts;
    }

    public function setCarts(?Cart $carts): static
    {
        $this->carts = $carts;

        return $this;
    }
}
