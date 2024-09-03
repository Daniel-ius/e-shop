<?php

namespace App\Controller\Crud;

use App\Entity\Category;
use App\Entity\Product;
use App\Repository\ProductsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route(path: '/api/v1/products',name:'app_product')]
class ProductController extends AbstractController
{
    private ValidatorInterface $validator;
    private ProductsRepository $productsRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(ValidatorInterface $validator, ProductsRepository $productsRepository, EntityManagerInterface $entityManager)
    {
        $this->validator = $validator;
        $this->productsRepository = $productsRepository;
        $this->entityManager = $entityManager;
    }

    #[Route(path: '/', methods: ['GET'])]
    public function getProducts(): JsonResponse
    {
        $response = [];
        $products = $this->productsRepository->findAll();
        foreach ($products as $product) {
            try {
                $response[] = json_encode($product,  JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES |
                    JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE
                    | JSON_NUMERIC_CHECK);
            } catch (\JsonException $e) {
                return new JsonResponse([
                    'success' => false,
                    'errors' => $e->getMessage()
                ]);
            }
        }
        return new JsonResponse([
            'success' => true,
            'data' => $response
        ]);
    }

    #[Route(path: '/{id}', methods: ['GET'])]
    public function getProductByID(Product $product): JsonResponse
    {
        try {
            return new JsonResponse([
                'success' => true,
                'data' => json_encode($product, JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES |
                    JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE
                    | JSON_NUMERIC_CHECK)
            ]);
        } catch (\JsonException $e) {
            return new JsonResponse([
                'success' => false,
                'errors' => $e->getMessage()
            ]);
        }
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(Product $product): JsonResponse
    {
        $this->entityManager->remove($product);
        $this->entityManager->flush();
        return new JsonResponse(['success' => true, 'message' => "Product was deleted"]);
    }

    #[Route(path: ['/{id}/edit', '/create'], methods: ['GET', 'POST', 'PUT'])]
    public function createEdit(Request $request, ?Product $productEdit): JsonResponse
    {
        $product = $productEdit ?? new Product();

        try {
            $data = json_decode($request->getContent(), true, 512,  JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES |
                JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE
                | JSON_NUMERIC_CHECK);
        } catch (\JsonException $e) {
            return new JsonResponse([
                'success' => false,
                'errors' => $e->getMessage()
            ]);
        }
        $properties = [];
        $values = [];
        foreach ($data as $property => $value) {
            $properties[] = $property;
            $values[] = $value;
            if (!property_exists($product, $property)) {
                return new JsonResponse([
                    'success' => false,
                    'errors' => "Property $property not found",
                    'properties' => $properties,
                    'values' => $values
                ]);
            }

            if ($property === 'category') {
                $product->setCategory($this->entityManager->getRepository(Category::class)->find($value));
            } else {
                $setter = 'set' . ucfirst($property);
                $product->$setter($value);
            }
        }
        $violation = $this->validator->validate($product);
        if (count($violation) > 0) {
            return new JsonResponse([
                'success' => false,
                'errors' => $violation,
                'properties' => $properties,
                'values' => $values
            ]);
        }
        $this->entityManager->persist($product);
        $this->entityManager->flush();
        return new JsonResponse([
            'success' => true,
            'properties' => $properties,
            'values' => $values
        ]);
    }
}
