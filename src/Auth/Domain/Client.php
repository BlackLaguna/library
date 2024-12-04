<?php

declare(strict_types=1);

namespace Auth\Domain;

use Auth\Domain\Client\ClientEmail;
use Auth\Domain\Client\ClientId;
use Auth\Domain\Client\ClientName;
use Symfony\Component\Uid\Uuid;

class Client
{
    public function __construct(
        private ClientId $uuid,
        private ClientName $name,
        private ClientEmail $email,
        private Password $password,
        Role ...$roles,
    ) {
    }

    public static function createNew(
        ClientName $name,
        ClientEmail $email,
        Password $password,
    ) {
        $roles[] = Role::USER;
        $uuid = Uuid::v4();

        return new self(
            ...$roles,
            uuid: $uuid,
            name: $name,
            email: $email,
            password: $password,
        );
    }
}