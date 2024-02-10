<?php
declare(strict_types=1);
namespace App\Storage;

use App\Entity\Carts;
use App\Repository\CartsRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class CartSessionStorage
{
    const CART_KEY_NAME='cart_id';

    private RequestStack $requestStack;
    private CartsRepository $cartsRepository;
    public function __construct(RequestStack $requestStack,CartsRepository $cartsRepository)
    {
        $this->requestStack=$requestStack;
        $this->cartsRepository=$cartsRepository;
    }

    public function getCartId():?int
    {
        return $this->requestStack->getSession()->get(self::CART_KEY_NAME);
    }

    public function setCart(Carts $cart):void
    {
        $this->requestStack->getSession()->set(self::CART_KEY_NAME,$cart->getId());
    }

    public function getCart():?Carts
    {
        return $this->cartsRepository->findOneBy([
            'id'=>$this->getCartId(),
            'status'=>Carts::STATUS_CART,
        ]);
    }
}