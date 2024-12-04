<?php

declare(strict_types=1);

namespace Auth\Infrastructure\Api\Resource\Request;

final class RegisterRequest
{
    public function __construct(
        public string $email,
        public string $password,
        public string $repeatedPassword,
        public string $name,
    ) {
    }
}