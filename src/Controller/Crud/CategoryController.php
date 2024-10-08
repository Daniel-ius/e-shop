<?php

namespace App\Controller\Crud;

use App\Entity\Category;
use App\Repository\CategoriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route(path:'/api/v1/categories',name:'app_category')]
class CategoryController extends AbstractController
{
    private ValidatorInterface $validator;
    private CategoriesRepository $categoriesRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(ValidatorInterface $validator, CategoriesRepository $categoriesRepository, EntityManagerInterface $entityManager)
    {
        $this->validator = $validator;
        $this->categoriesRepository = $categoriesRepository;
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'app_category_index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $response = [];
        $categories = $this->categoriesRepository->findAll();
        foreach ($categories as $category) {
            try {
                $response[] = json_encode($category, JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES |
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
            'data' => $response,
        ]);
    }

    #[Route('/{id}', name: 'app_category_show', methods: ['GET'])]
    public function show(Category $category): Response
    {
        return new JsonResponse([
            'success' => true,
            'data' => $category,
        ]);
    }

    #[Route('/create', name: 'app_category_new', methods: ['GET', 'POST'])]
    public function create(Request $request,): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES |
                JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE
                | JSON_NUMERIC_CHECK);
        } catch (\JsonException $e) {
            return new JsonResponse([
                'success' => false,
                'errors' => $e->getMessage()
            ]);
        }
        $category = new Category();
        $category->setName($data['name']);
        $violations = $this->validator->validate($category);
        if (count($violations) > 0) {
            return $this->json([
                'success' => false,
                'errors' => $violations
            ]);
        }
        $this->entityManager->persist($category);
        $this->entityManager->flush();
        return new JsonResponse([
            'success' => true,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_category_edit', methods: ['PUT'])]
    public function edit(Request $request, Category $category): Response
    {
        try {
            $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES |
                JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE
                | JSON_NUMERIC_CHECK);
        } catch (\JsonException $e) {
            return new JsonResponse([
                'success' => false,
                'errors' => $e->getMessage()
            ]);
        }
        $category->setName($data['name']);
        $violations = $this->validator->validate($category);
        if (count($violations) > 0) {
            return new JsonResponse([
                'success' => false,
                'errors' => $violations
            ]);
        }
        $this->entityManager->persist($category);
        $this->entityManager->flush();
        return new JsonResponse(['success' => true]);
    }

    #[Route('/{id}', name: 'app_category_delete', methods: ['DELETE'])]
    public function delete(Category $category): Response
    {
        $this->entityManager->remove($category);
        $this->entityManager->flush();
        return new JsonResponse(['success' => true]);
    }
}
