<?php

declare(strict_types=1);

namespace Auth\Infrastructure\Api\Resource\Response;

final readonly class ClientToken
{
    public function __construct(public string $token)
    {
    }
}