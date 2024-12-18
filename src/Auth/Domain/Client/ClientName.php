<?php

declare(strict_types=1);

namespace Auth\Domain\Client;

final readonly class ClientName
{
    public function __construct(
        public string $name,
    ) {
    }

    public static function fromString(string $name): self
    {
        return new self($name);
    }
}