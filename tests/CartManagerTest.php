<?php

namespace App\Tests;

use App\Entity\CartItems;
use App\Entity\Carts;
use App\Entity\Products;
use App\Factory\CartFactory;
use App\Manager\CartManager;
use App\Storage\CartSessionStorage;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class CartManagerTest extends TestCase
{

    public function testGetCurrentCartWithEmptySession(): void
    {
        $cartSessionStorageMock=$this->createMock(CartSessionStorage::class);
        $cartSessionStorageMock->expects($this->once())
            ->method('getCart')
            ->willReturn(null);

        $cartFactoryMock=$this->createMock(CartFactory::class);
        $cartFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn(new Carts());

        $entityManagerMock=$this->createMock(EntityManagerInterface::class);

        $cartManagerMock=new CartManager($cartSessionStorageMock,$cartFactoryMock,$entityManagerMock);

        $cart=$cartManagerMock->getCurrentCart();

        $this->assertInstanceOf(Carts::class,$cart);
    }

    public function test_getCurrentCartWithExistingSession()
    {
        $existingCart = new Carts();

        $cartSessionStorageMock = $this->createMock(CartSessionStorage::class);
        $cartSessionStorageMock->expects($this->once())
            ->method('getCart')
            ->willReturn($existingCart);

        $cartFactoryMock = $this->createMock(CartFactory::class);
        $cartFactoryMock->expects($this->never())
            ->method('create');

        $entityManagerMock = $this->createMock(EntityManagerInterface::class);

        $cartManager = new CartManager($cartSessionStorageMock,$cartFactoryMock, $entityManagerMock);

        $cart = $cartManager->getCurrentCart();

        $this->assertSame($existingCart, $cart);
    }

    public function test_save()
    {
        $cart = new Carts();

        $cartSessionStorageMock = $this->createMock(CartSessionStorage::class);
        $cartSessionStorageMock->expects($this->once())
            ->method('setCart')
            ->with($cart);

        $entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $entityManagerMock->expects($this->once())
            ->method('persist')
            ->with($cart);
        $entityManagerMock->expects($this->once())
            ->method('flush');

        $cartManager = new CartManager($cartSessionStorageMock,new CartFactory(), $entityManagerMock);

        $cartManager->save($cart);
    }
    public function test_removeItem()
    {
        $cart = new Carts();
        $item = new Products();
        $cartItem= new CartItems();
         $cartItem->setItem($item);
         $cartItem->setQuantity(1);
         $cartItem->setCarts($cart);
         $cart->addItem($cartItem);


        $cartSessionStorageMock = $this->createMock(CartSessionStorage::class);
        $cartSessionStorageMock->expects($this->once())
            ->method('getCart')
            ->willReturn($cart);
        $cartSessionStorageMock->expects($this->once())
            ->method('setCart')
            ->with($cart);

        $entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $entityManagerMock->expects($this->once())
            ->method('remove')
            ->with($cartItem);
        $entityManagerMock->expects($this->once())
            ->method('flush');

        $cartManager = new CartManager($cartSessionStorageMock,new CartFactory(), $entityManagerMock);

        $cartManager->removeItem($cartItem);
    }
}
