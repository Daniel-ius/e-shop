<?php

namespace App\Controller;

use App\Entity\OrderHistory;
use App\Entity\Product;
use App\Entity\User;
use App\Factory\CartFactory;
use App\Manager\CartManager;
use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Attributes as OA;
use phpDocumentor\Reflection\DocBlock\Description;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[OA\Info(version: "1.0.0", description: "Cart API", title: "Cart API")]
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

    #[OA\Post(
        path: "/cart/add",
        description: "Adds an item to the currently logged-in user's cart",
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(properties:
                [new OA\Property(property: "productId", description: "Product ID", type: "integer", example: 1),
            new OA\Property(property: "quantity", description: "Product quantity", type: "integer", example: 5),])),
        responses: [
            new OA\Response(response: 200, description: "Item added to cart"),
            new OA\Response(response: 404, description: "Product not found"),
        ]
    )]
    #[Route('/add', name: 'app_to_cart', methods: ['POST'])]
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

    #[OA\Get(
        path: '/cart/get',
        description: "Gets Cart",
        responses: [
            new OA\Response(
                response: 200,
                description: "Cart entity")
        ]
    )]
    #[Route('/get', name: 'app_get_cart', methods: ['GET'])]
    public function getCart(Request $request): JsonResponse
    {
        $user = $this->security->getUser();
        $cart = $this->cartManager->getCurrentCartForUser($user);
        return $this->json([
            'success' => true,
            'data' => $cart,
        ]);
    }

    #[OA\GET(
        path: '/cart/orderHistory',
        description: "Gets order histories",
        responses:[
            new OA\Response(
                response: 200,description: "Order history entity"
            )
        ]
    )]
    #[Route('/orderHistory', name: 'app_get_order_history', methods: ['GET'])]
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

    #[OA\Post(
        path: '/cart/checkout',
        description: "Checks out an active users cart",
        responses: [
            new OA\Response(response: 200,description: "Check out successfully"),
            new OA\Response(response: 400,description: "Check out failed")
        ]
    )]
    #[Route('/checkout', name: 'app_checkout', methods: ['POST'])]
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
