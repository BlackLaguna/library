<?php

declare(strict_types=1);

namespace Auth\Application\CQRS\Command;

final readonly class RegisterClientCommand
{
    public function __construct(
        public string $email,
        public string $password,
        public string $name,
    ) {
    }
}