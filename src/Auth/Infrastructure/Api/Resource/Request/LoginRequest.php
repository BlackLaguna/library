<?php

declare(strict_types=1);

namespace Auth\Infrastructure\Api\Resource\Request;

final readonly class LoginRequest
{
    public function __construct(
        public string $email,
        public string $password
    ) {
    }
}