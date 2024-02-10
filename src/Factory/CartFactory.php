<?php
declare(strict_types=1);
namespace App\Factory;

use App\Entity\CartItems;
use App\Entity\Carts;
use App\Entity\Products;

class CartFactory
{
    public function create():Carts
    {
        $cart = new Carts();
        $cart
            ->setStatus(Carts::STATUS_CART)
            ->setCreatedAt(new \DateTime())
            ->setUpdatedAt(new \DateTime())
            ->setTotal();
        return $cart;
    }

    public function createItem(Products $products):CartItems
    {
        $item=new CartItems();
        $item->setItem($products);
        $item->setCarts($this->create());
        $item->setQuantity(1);
        $item->setTotalPrice();
        return $item;
    }
}