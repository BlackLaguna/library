<?php

declare(strict_types=1);

namespace Books\Domain\Client;

use Symfony\Component\Uid\Uuid;

final readonly class ClientId
{
    private function __construct(public string $email)
    {
    }

    public static function fromString(string $email): self
    {
        return new self($email);
    }

    public function equals(self $clientId): bool
    {
        return $this->email === $clientId->email;
    }
}