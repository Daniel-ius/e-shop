<?php

namespace App\Factory;

use App\Entity\Users;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFactory
{
    public function create(array $data,UserPasswordHasherInterface $hasher): Users
    {
        $user=new Users();
        $user->setUsername($data['username'])
            ->setRoles(["ROLE_USER"])
            ->setEmail($data['email'])
            ->setPassword($hasher->hashPassword($user,$data['password']))
            ->setFirstName($data['firstName'])
            ->setLastName($data['lastName'])
            ->setPhoneNumber($data['phoneNumber'])
            ->setStreet($data['street'])
            ->setCity($data['city'])
            ->setZipCode($data['zipCode'])
            ->setAddress()
        ;
        $user->setStatus(Users::STATUS_ACTIVE);

        $user->setCreationDate(new \DateTime());
        return $user;
    }
}