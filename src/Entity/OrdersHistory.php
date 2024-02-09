<?php

namespace App\Entity;

use App\Repository\OrdersHistoryRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrdersHistoryRepository::class)]
class OrdersHistory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'ordersHistories')]
    private ?Users $user = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Carts $cart = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?Users
    {
        return $this->user;
    }

    public function setUser(?Users $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getCart(): ?Carts
    {
        return $this->cart;
    }

    public function setCart(?Carts $cart): static
    {
        $this->cart = $cart;

        return $this;
    }

    public function getCarts(): ?Carts
    {
        return $this->carts;
    }

    public function setCarts(?Carts $carts): static
    {
        // unset the owning side of the relation if necessary
        if ($carts === null && $this->carts !== null) {
            $this->carts->setOrderhistory(null);
        }

        // set the owning side of the relation if necessary
        if ($carts !== null && $carts->getOrderhistory() !== $this) {
            $carts->setOrderhistory($this);
        }

        $this->carts = $carts;

        return $this;
    }
}
