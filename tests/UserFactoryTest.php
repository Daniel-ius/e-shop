<?php

namespace App\Tests;

use App\Entity\Users;
use App\Factory\UserFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFactoryTest extends TestCase
{
    public function testCreateUser()
    {
        $hasherMock = $this->createMock(UserPasswordHasherInterface::class);
        $hasherMock->expects($this->once())
            ->method('hashPassword')
            ->with($this->isInstanceOf(Users::class), $this->equalTo('password'))
            ->willReturn('hashed_password');

        $userData = [
            'username' => 'john.doe',
            'email' => 'john.doe@example.com',
            'password' => 'password',
            'firstName' => 'John',
            'lastName' => 'Doe',
            'phoneNumber' => '123-456-7890',
            'street' => '123 Main St',
            'city' => 'Anytown',
            'zipCode' => '12345',
        ];

        $factory = new UserFactory();
        $user = $factory->create($userData, $hasherMock);

        $this->assertEquals('john.doe', $user->getUsername());
        $this->assertEquals('hashed_password', $user->getPassword());
        $this->assertEquals('john.doe@example.com', $user->getEmail());
        $this->assertEquals(['ROLE_USER'], $user->getRoles());
        $this->assertEquals('John', $user->getFirstName());
        $this->assertEquals('Doe', $user->getLastName());
        $this->assertEquals('123-456-7890', $user->getPhoneNumber());
        $this->assertEquals('123 Main St', $user->getStreet());
        $this->assertEquals('Anytown', $user->getCity());
        $this->assertEquals('12345', $user->getZipCode());
        $this->assertEquals(Users::STATUS_ACTIVE, $user->getStatus());
        $this->assertInstanceOf(\DateTime::class, $user->getCreationDate());
    }
}
