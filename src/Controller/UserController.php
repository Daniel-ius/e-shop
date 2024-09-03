<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path:"/api/v1")]
class UserController extends AbstractController
{
    private Security $security;
    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $hasher;

    public function __construct(Security $security, EntityManagerInterface $entityManager, UserPasswordHasherInterface $hasher)
    {
        $this->security = $security;
        $this->entityManager = $entityManager;
        $this->hasher = $hasher;
    }


    #[Route('/user/data', name: 'app_user')]
    public function index(): Response
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['username' => $this->security->getUser()->getUserIdentifier()]);
        if (!isset($user)){
            return new JsonResponse([
                'success' => false,
                'errors' => "User doesn't exits"
            ]);
        }
        return $this->json($user);
    }

    #[Route(path: ['/edit'], methods: ['PATCH'])]
    public function editUser(Request $request): JsonResponse
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['username' => $this->security->getUser()->getUserIdentifier()]);
        if (!isset($user)){
            return new JsonResponse([
                'success' => false,
                'errors' => "User doesn't exits"
            ]);
        }
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
        $properties = [];
        $notAllowedProperties = [
            'password', 'roles', 'id'
        ];
        foreach ($data as $property => $value) {
            $properties[] = $property;
            if (!property_exists($user, $property)) {
                return new JsonResponse([
                    'success' => false,
                    'errors' => "Property $property not found",
                    'properties' => $properties,
                ]);
            }
            if (in_array($property, $notAllowedProperties, true)) {
                return new JsonResponse([
                    'success' => false,
                    'errors' => "Cant use this to change $property",
                    'properties' => $properties,
                ]);
            }

            $setter = 'set' . ucfirst($property);
            $user->$setter($value);
        }
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return new JsonResponse([
            'success' => true,
            'properties' => $properties,
        ]);
    }

    #[Route(path: ['/change/password'], methods: ['PUT'])]
    public function changePassword(Request $request): JsonResponse
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['username' => $this->security->getUser()->getUserIdentifier()]);
        if (!isset($user)){
            return new JsonResponse([
                'success' => false,
                'errors' => "User doesn't exits"
            ]);
        }
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
        $user->setPassword($this->hasher->hashPassword($user, $data['password']));
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return new JsonResponse([
            'success' => true,
            'message' => 'Password has been changed successfully'
        ]);
    }

}
