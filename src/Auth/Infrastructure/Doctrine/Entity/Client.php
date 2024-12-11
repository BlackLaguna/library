<?php

declare(strict_types=1);

namespace Auth\Infrastructure\Doctrine\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity]
#[ORM\Table(name: 'clients')]
class Client implements UserInterface
{
    public function __construct(
        #[ORM\Id]
        #[ORM\Column(type: 'string', length: 255)]
        private string $email,
        #[ORM\Column(type: 'text', length: 255)]
        private string $name,
        #[ORM\Column(type: 'string', length: 255)]
        private string $password,
        #[ORM\Column(type: 'json')]
        private array $roles = [],
    ) {
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function eraseCredentials()
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }
}