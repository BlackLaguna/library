<?php

declare(strict_types=1);

namespace Auth\Domain\Client;

readonly class ClientToken
{
    public function __construct(public string $token)
    {
    }

    public static function fromString(string $token): self
    {
        return new self($token);
    }
}