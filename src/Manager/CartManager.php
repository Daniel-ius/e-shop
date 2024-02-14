<?php

namespace App\Manager;

use App\Entity\Cart;
use App\Entity\Product;
use App\Factory\CartFactory;
use App\Storage\CartSessionStorage;
use Doctrine\ORM\EntityManagerInterface;

class CartManager
{
    private CartSessionStorage $cartSessionStorage;
    private CartFactory $cartFactory;
    private EntityManagerInterface $entityManager;
    public function __construct(CartSessionStorage $cartSessionStorage, CartFactory $cartFactory, EntityManagerInterface $entityManager)
    {
        $this->cartSessionStorage = $cartSessionStorage;
        $this->cartFactory=$cartFactory;
        $this->entityManager=$entityManager;
    }
    public function getCurrentCart():Cart
    {
        $cart=$this->cartSessionStorage->getCart();
        if (!$cart){
            $cart=$this->cartFactory->create();
        }
        return $cart;
    }

    public function save(Cart $cart):void
    {
        $this->entityManager->persist($cart);
        $this->entityManager->flush();
        $this->cartSessionStorage->setCart($cart);
    }

    public function removeItem($item):void
    {
        $cart=$this->cartSessionStorage->getCart();
        $cart->removeItem($item);
        $this->entityManager->remove($item);
        $this->entityManager->flush();
        $this->cartSessionStorage->setCart($cart);
    }
    public function getManager():CartManager
    {
        return $this;
    }
}