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
    private ?Products $item = null;

    #[ORM\ManyToOne(inversedBy: 'items')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Carts $carts = null;
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

    public function getItem(): ?Products
    {
        return $this->item;
    }

    public function setItem(?Products $item): static
    {
        $this->item = $item;

        return $this;
    }

    public function getCarts(): ?Carts
    {
        return $this->carts;
    }

    public function setCarts(?Carts $carts): static
    {
        $this->carts = $carts;

        return $this;
    }
}
