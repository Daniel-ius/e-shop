<?php

namespace App\Controller;

use App\Factory\UserFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path:"/api/v1")]
class RegistrationController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $userPasswordHasher;

    private UserFactory $userFactory;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher, UserFactory $userFactory)
    {
        $this->entityManager = $entityManager;
        $this->userPasswordHasher = $userPasswordHasher;
        $this->userFactory=$userFactory;
    }

    #[Route('/registration', name: 'app_registration', methods: ['POST'])]
    public function newUser(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            return new JsonResponse([
                'success' => false,
                'errors' => $e->getMessage()
            ]);
        }
        $user=$this->userFactory->create($data,$this->userPasswordHasher);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return new JsonResponse([
            'success'=>true,
            'message'=>'User created',
        ]);
    }
}
