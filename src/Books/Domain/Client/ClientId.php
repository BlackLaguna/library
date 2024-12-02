<?php

declare(strict_types=1);

namespace Books\Domain\Client;

use Symfony\Component\Uid\Uuid;

final readonly class ClientId
{
    private function __construct(public Uuid $uuid)
    {
    }

    public static function fromUuid(Uuid $uuid): self
    {
        return new self($uuid);
    }

    public function equals(self $clientId): bool
    {
        return $this->uuid->equals($clientId->uuid);
    }
}