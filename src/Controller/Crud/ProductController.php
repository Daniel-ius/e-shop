<?php

namespace App\Controller\Crud;

use App\Entity\Category;
use App\Entity\Product;
use App\Repository\ProductsRepository;
use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route(path: '/products')]
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

    #[OA\Get(
        path: '/products',
        description: 'Gets all products',
        responses:[
            new OA\Response(
                response: 200,
                description: 'success'
            )
        ]
    )]
    #[Route(path: '/', methods: ['GET'])]
    public function getProducts(): JsonResponse
    {
        $response = [];
        $products = $this->productsRepository->findAll();
        foreach ($products as $product) {
            $response[] = json_encode($product);
        }
        return $this->json([
            'success' => true,
            'data' => $response
        ]);
    }
    #[OA\Get(
        path: '/products/id',
        description: 'Gets a product by that id',
        responses:[
            new OA\Response(
                response: 200,
                description: 'success'
            )
        ]
    )]
    #[Route(path: '/{id}', methods: ['GET'])]
    public function getProductByID(Product $product): JsonResponse
    {
        return $this->json([
            'success' => true,
            'data' => json_encode($product)
        ]);
    }
    #[OA\Delete(
        path: '/products/id',
        description: 'Deletes a product by id',
        responses: [
            new OA\Response(response: 200,description: 'success')
        ]
    )]
    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(Product $product): JsonResponse
    {
        $this->entityManager->remove($product);
        $this->entityManager->flush();
        return new JsonResponse(['success' => true]);
    }

    #[OA\Get(
        path: '/products/[create,id/edit]',
        description: 'Creates a new product or edits already existing product',
        responses:[
            new OA\Response(
                response: 200, description: 'success'
            )
        ]
    )]
    #[Route(path: ['/{id}/edit', '/create'], methods: ['GET', 'POST', 'PUT'])]
    public function create_edit(Request $request, ?Product $productEdit): JsonResponse
    {
        $product = $productEdit ?? new Product();
        $data = json_decode($request->getContent(), true);
        $properties = [];
        $values = [];
        foreach ($data as $property => $value) {
            $properties[] = $property;
            $values[] = $value;
            if (property_exists($product, $property)) {
                if($property==='category'){
                    $product->setCategory($this->entityManager->getRepository(Category::class)->find($value));
                }else{
                    $setter = 'set' . ucfirst($property);
                    $product->$setter($value);
                }
            } else {
                return new JsonResponse([
                    'success' => false,
                    'errors' => "Property $property not found",
                    'properties' => $properties,
                    'values' => $values
                ]);
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
