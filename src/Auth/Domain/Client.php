<?php

declare(strict_types=1);

namespace Auth\Domain;

use Auth\Domain\Client\ClientEmail;
use Auth\Domain\Client\ClientId;
use Auth\Domain\Client\ClientName;
use Auth\Domain\Client\ClientPassword;
use Symfony\Component\Uid\Uuid;

class Client
{
    /** @var Role[] */
    private array $roles;

    public function __construct(
        private ClientId $id,
        private ClientName $name,
        Role ...$roles,
    ) {
        $this->roles = $roles;
    }

    public static function createNew(
        ClientName $name,
        ClientEmail $email,
        ClientPassword $password,
    ) {
        $roles[] = Role::USER;
        $uuid = ClientId::createNew($email, $password);

        return new self(
            ...$roles,
            id: $uuid,
            name: $name,
        );
    }

    public function hasRole(Role $role): bool
    {
        return in_array(
            needle: $role,
            haystack: $this->roles,
            strict: true
        );
    }
}