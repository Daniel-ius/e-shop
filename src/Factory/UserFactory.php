<?php

namespace App\Factory;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFactory
{
    public function create(array $data,UserPasswordHasherInterface $hasher): User
    {
        $role= $data['roles'] ?? ["ROLE_USER"];
        $user=new User();
        $user->setUsername($data['username'])
            ->setRoles($role)
            ->setEmail($data['email'])
            ->setPassword($hasher->hashPassword($user,$data['password']))
            ->setFirstName($data['firstName'])
            ->setLastName($data['lastName'])
            ->setPhoneNumber($data['phoneNumber'])
            ->setStreet($data['street'])
            ->setCity($data['city'])
            ->setZipCode($data['zipCode'])
            ->setAddress()
            ->setCreationDate(new \DateTime())
            ->setStatus(User::STATUS_ACTIVE)
        ;
        return $user;
    }
}