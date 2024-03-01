<?php
declare(strict_types=1);
namespace App\Factory;

use App\Entity\CartItems;
use App\Entity\Cart;
use App\Entity\Product;

class CartFactory
{
    public function create():Cart
    {
        $cart = new Cart();
        $cart
            ->setStatus(Cart::STATUS_CART)
            ->setCreatedAt(new \DateTime())
            ->setUpdatedAt(new \DateTime())
            ->setTotal();
        return $cart;
    }

    public function createItem(Product $products,Cart $cart,?int $quantity):CartItems
    {
        $item=new CartItems();
        $item->setItem($products);
        $item->setCarts($cart);
        $item->setQuantity($quantity ?? 1);
        $item->setTotalPrice();
        return $item;
    }
}