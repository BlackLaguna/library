<?php

namespace Auth\Infrastructure\Api\Security;

use Auth\Infrastructure\Doctrine\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class ClientProvider implements UserProviderInterface
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function refreshUser(UserInterface $user)
    {
    }

    public function supportsClass($class)
    {
        return Client::class === $class;
    }

    private function fetchUser($username)
    {
        return $this->entityManager->find(Client::class, $username);
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        return $this->entityManager->find(Client::class, $identifier);
    }
}