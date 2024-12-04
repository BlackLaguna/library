<?php

declare(strict_types=1);

namespace Auth\Domain\Client;

use Symfony\Component\Uid\Uuid;

final readonly class ClientId
{
    public function __construct(
        public Uuid $uuid,
    ) {
    }
}