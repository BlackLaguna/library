<?php
namespace Auth\Infrastructure\Security;

use Auth\Infrastructure\Doctrine\Entity\Client;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class ClientProvider implements UserProviderInterface
{
    public function refreshUser(UserInterface $user)
    {

    }

    public function supportsClass($class)
    {
        return Client::class === $class;
    }

    private function fetchUser($username)
    {
        dd($username);
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        dd($identifier);
    }
}