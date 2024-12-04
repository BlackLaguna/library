<?php

declare(strict_types=1);

namespace Auth\Domain;

use Auth\Domain\Client\ClientId;
use Auth\Domain\Client\ClientPassword;
use Auth\Domain\Services\IsClientExistService;
use Auth\Domain\Services\TokenEncrypterService;

class ClientToken
{
    public function __construct(private string $token) {
    }


    public function createNewForUser(
        ClientId $clientId,
        TokenEncrypterService $tokenEncrypter,
        IsClientExistService $isClientExistService,
    ): self {
        if (!$isClientExistService->isClientExist($clientId)) {
            throw new \Exception();
        }

        if ()

        return new self($tokenEncrypter->encrypt((string) $clientId));
    }

    public function validateToken(self $token, TokenEncrypterService $tokenEncrypter): bool
    {

    }

    public function __toString(): string
    {

    }
}