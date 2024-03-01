<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\User;
use App\Factory\CartFactory;
use App\Manager\CartManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/cart', name: 'app_cart')]
class CartController extends AbstractController
{
    private CartManager $cartManager;
    private EntityManagerInterface $entityManager;
    private Security $security;
    private CartFactory $cartFactory;

    public function __construct(CartManager $cartManager, EntityManagerInterface $entityManager, Security $security, CartFactory $cartFactory)
    {
        $this->cartManager = $cartManager;
        $this->entityManager = $entityManager;
        $this->security = $security;
        $this->cartFactory = $cartFactory;
    }

    #[Route('/add', name: 'app_to_cart', methods: ['POST'])]
    public function addToCart(Request $request): JsonResponse
    {
        $user = $this->security->getUser();
        $data = json_decode($request->getContent(), true);
        $product = $this->entityManager->getRepository(Product::class)->find($data['productId']);
        if (!$product) {
            return $this->json([
                'success' => false,
                'message' => 'Product not found.',
            ]);
        }
        $cart = $this->cartManager->getCurrentCartForUser($user);
        $cart->addItem($this->cartFactory->createItem($product, $cart, $data['quantity']));
        $cart->setTotal();
        $this->entityManager->flush();
        $this->cartManager->save($cart);
        return $this->json([
            'success' => true,
            'message' => 'Item added to cart successfully.',
        ]);
    }

    #[Route('/get', name: 'app_cart', methods: ['GET'])]
    public function getCart(Request $request): JsonResponse
    {
        $user = $this->security->getUser();
        $cart = $this->cartManager->getCurrentCartForUser($user);
        if (!$cart) {
            return $this->json([
                'success' => false,
                'message' => 'No items in your cart.',
            ]);
        }
        return $this->json([
            'success' => true,
            'cart' => $cart,
        ]);
    }
}
