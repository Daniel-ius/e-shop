<?php

namespace App\Controller;

use App\Entity\OrderHistory;
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

#[Route(path:['/api/v1/cart'],name:'app_cart')]
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

    #[Route(path:['/'], methods: ['GET'])]
    public function getCart(Request $request): JsonResponse
    {
        $user = $this->security->getUser();
        $cart = $this->cartManager->getCurrentCartForUser($user);
        return $this->json([
            'success' => true,
            'data' => $cart,
        ]);
    }

    #[Route(path: ['/add'], methods: ['POST'])]
    public function addToCart(Request $request): JsonResponse
    {
        $user = $this->security->getUser();
        try {
            $data = json_decode($request->getContent(), true, 512,  JSON_THROW_ON_ERROR|JSON_UNESCAPED_SLASHES|
                JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE
                | JSON_NUMERIC_CHECK);
        } catch (\JsonException $e) {
            return new JsonResponse([
                'success' => false,
                'errors' => $e->getMessage()
            ]);
        }
        $product = $this->entityManager->getRepository(Product::class)->find($data['productId']);
        if (!$product) {
            return $this->json([
                'success' => false,
                'message' => 'Product not found.',
            ]);
        }
        $cart = $this->cartManager->getCurrentCartForUser($user);
        $this->cartManager->addItem($user, $this->cartFactory->createItem($product, $cart, $data['quantity']));
        $this->cartManager->recalculateTotalPrice($cart);
        $this->entityManager->flush();
        $this->cartManager->save($cart);
        return $this->json([
            'success' => true,
            'message' => 'Item added to cart successfully.',
        ]);
    }

    #[Route(path:['/orders'], methods: ['GET'])]
    public function getOrderHistory(Request $request): JsonResponse
    {
        $user = $this->security->getUser();
        $user = $this->entityManager->getRepository(User::class)->find($user);
        $orderHistory = $this->entityManager->getRepository(OrderHistory::class)->findBy(['user' => $user->getId()]);
        return $this->json([
            'success' => true,
            'data' => $orderHistory,
        ]);
    }

    #[Route(path:['/checkout'], methods: ['POST'])]
    public function checkout(Request $request): JsonResponse
    {
        $user = $this->security->getUser();
        $cart = $this->cartManager->getCurrentCartForUser($user);
        if ($this->cartManager->checkoutCart($user, $cart)) {
            return $this->json([
                'success' => true,
            ]);
        }
        return $this->json([
            'success' => false,
        ]);
    }
}
