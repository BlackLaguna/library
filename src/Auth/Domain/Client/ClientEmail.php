<?php

declare(strict_types=1);

namespace Auth\Domain\Client;

final readonly class ClientEmail
{
    public function __construct(
        public string $email,
    ) {
    }
}