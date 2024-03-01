<?php

namespace App\Entity;

use App\Repository\UsersRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotBlank;

#[ORM\Entity(repositoryClass: UsersRepository::class)]
#[ORM\Table(name: 'users')]
#[UniqueEntity(fields: ['username'], message: 'Username already exist')]
#[UniqueEntity(fields: ['email'], message: 'Email already exist')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    const STATUS_ACTIVE = 'active';
    const STATUS_DEACTIVATED = 'deactivated';
    const STATUS_DELETED = 'deleted';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    #[ORM\Column(length: 180, unique: true)]
    #[NotBlank]
    private ?string $username = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[NotBlank]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    #[NotBlank]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    #[NotBlank]
    private ?string $lastName = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[NotBlank]
    #[Assert\Email]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    #[NotBlank]
    private ?string $phoneNumber = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[NotBlank]
    private ?string $address = null;

    #[ORM\Column(length: 255)]
    #[NotBlank]
    private ?string $street = null;

    #[ORM\Column(length: 255)]
    #[NotBlank]
    private ?string $city = null;

    #[ORM\Column(length: 255)]
    #[NotBlank]
    private ?string $zipCode = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTimeInterface $lastLogin = null;

    #[ORM\Column(length: 255)]
    #[NotBlank]
    private ?string $status = null;

    #[ORM\OneToMany(targetEntity: OrderHistory::class, mappedBy: 'user')]
    private Collection $ordersHistories;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[NotBlank]
    private ?\DateTimeInterface $creationDate = null;

    #[ORM\OneToMany(targetEntity: Cart::class, mappedBy: 'user',cascade: ['persist','remove'])]
    private Collection $carts;

    public function __construct()
    {
        $this->ordersHistories = new ArrayCollection();
        $this->carts=new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string)$this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): static
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(): static
    {
        $this->address = $this->street . ", " . $this->city . ", " . $this->zipCode;

        return $this;
    }

    public function getCarts(): Collection
    {
        return $this->carts;
    }

    public function setCarts(Collection $carts): void
    {
        $this->carts = $carts;
    }
    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): static
    {
        $this->street = $street;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    public function setZipCode(string $zipCode): static
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    public function getLastLogin(): ?DateTimeInterface
    {
        return $this->lastLogin;
    }

    public function setLastLogin(?DateTimeInterface $lastLogin): static
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection<int, OrderHistory>
     */
    public function getOrdersHistories(): Collection
    {
        return $this->ordersHistories;
    }

    public function addOrdersHistory(OrderHistory $ordersHistory): static
    {
        if (!$this->ordersHistories->contains($ordersHistory)) {
            $this->ordersHistories->add($ordersHistory);
            $ordersHistory->setUser($this);
        }

        return $this;
    }

    public function removeOrdersHistory(OrderHistory $ordersHistory): static
    {
        if ($this->ordersHistories->removeElement($ordersHistory)) {
            // set the owning side to null (unless already changed)
            if ($ordersHistory->getUser() === $this) {
                $ordersHistory->setUser(null);
            }
        }

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(\DateTimeInterface $creationDate): static
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'roles' => $this->getRoles(), // Filter sensitive roles if needed
            'firstName' => $this->getFirstName(),
            'lastName' => $this->getLastName(),
            'email' => $this->getEmail(),
            'phoneNumber' => $this->getPhoneNumber(),
            'address' => $this->getAddress(),
            'createdAt' => $this->getCreationDate()->format('Y-m-d H:i:s'),
            'carts' => $this->getCarts()->map(fn (Cart $cart) => [
                'id' => $cart->getId(),
                'status' => $cart->getStatus(),
            ])->toArray(),
    ];
    }
}