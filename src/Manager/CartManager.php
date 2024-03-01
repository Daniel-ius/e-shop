<?php

namespace App\Manager;

use App\Entity\Cart;
use App\Entity\Product;
use App\Entity\User;
use App\Factory\CartFactory;
use App\Storage\CartSessionStorage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

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
    public function getCurrentCartForUser($user):?Cart
    {
        $cart=$this->entityManager->getRepository(Cart::class)->findOneBy(['user'=>$user]);
        if (!$cart){
            $cart=$this->cartFactory->create();
            $cart->setUser($user);
            $this->save($cart);
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
        $this->save($cart);
    }

    public function addItem($item): void
    {
        $cart=$this->cartSessionStorage->getCart();
        $cart->addItem($item);
        $this->save($cart);

    }
    public function getManager():CartManager
    {
        return $this;
    }


}