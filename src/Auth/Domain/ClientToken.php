<?php

declare(strict_types=1);

namespace Auth\Domain;

use Auth\Domain\Client\ClientId;

class ClientToken
{
    public function __construct(private string $token) {
    }


    public function loginUser(ClientId $clientId, Password $password): void
    {

    }
}