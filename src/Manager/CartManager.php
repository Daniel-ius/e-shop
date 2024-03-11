<?php

namespace App\Manager;

use App\Entity\Cart;
use App\Entity\CartItems;
use App\Entity\OrderHistory;
use App\Entity\Product;
use App\Entity\User;
use App\Factory\CartFactory;
use App\Storage\CartSessionStorage;
use Cassandra\Date;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use function Symfony\Component\Translation\t;

class CartManager
{
    private CartFactory $cartFactory;
    private EntityManagerInterface $entityManager;
    public function __construct(CartFactory $cartFactory, EntityManagerInterface $entityManager)
    {
        $this->cartFactory=$cartFactory;
        $this->entityManager=$entityManager;
    }
    public function getCurrentCartForUser($user):Cart
    {
        $cart = $this->entityManager->getRepository(Cart::class)->findOneBy(['user' => $user,'status'=>Cart::STATUS_CART]);
        if (!$cart) {
            $cart = $this->cartFactory->create();
            $cart->setUser($user);
            $this->save($cart);
        }
        return $cart;
    }


    public function removeItem(UserInterface $user,CartItems $item):void
    {
        $cart=$this->getCurrentCartForUser($user);
        $cart->removeItem($item);
        $cart->setUpdatedAt(new \DateTime());
        $this->entityManager->remove($item);
        $this->entityManager->flush();
        $this->save($cart);
    }

    public function addItem(UserInterface $user, CartItems $item): void
    {
        $cart=$this->getCurrentCartForUser($user);
        if ($this->checkIfItemExistsAndIncreaseQuantity($cart,$item)){
            $cart->addItem($item);
            $cart->setUpdatedAt(new \DateTime());
            $this->recalculateTotalPrice($cart);
        }
        $cart->setUpdatedAt(new \DateTime());
        $this->recalculateTotalPrice($cart);
    }

    public function checkIfItemExistsAndIncreaseQuantity(Cart $cart,CartItems $product): bool
    {
        $items=$cart->getItems();
        foreach ($items as $item) {
            if($item->getItem()->getId()==$product->getItem()->getId()){
                $item->setQuantity($item->getQuantity()+1);
                $item->setTotalPrice();
                $this->save($cart);
                return false;
            }
        }
        return true;
    }


    public function recalculateTotalPrice(Cart $cart): void
    {
        $cart->resetTotal();
        foreach ($cart->getItems() as $item) {
            $item->setTotalPrice();
            $cart->setTotal();
        }
        $this->entityManager->flush();
    }

    public function checkoutCart($user,Cart $cart):bool
    {
        if ($cart->getItems()->count() == 0) {
            return false;
        }
        $cart->setStatus(Cart::STATUS_CHECKOUT);
        $orderHistory=new OrderHistory();
        $orderHistory->setUser($user);
        $orderHistory->setCart($cart);
        $this->entityManager->persist($orderHistory);
        $this->entityManager->flush();
        return true;
    }

    public function save(Cart $cart):void
    {
        $this->entityManager->persist($cart);
        $this->entityManager->flush();
    }

}