<?php

declare(strict_types=1);

namespace Auth\Application\CQRS\Query;

final readonly class GetClientTokenForUserQuery
{
    public function __construct(public string $email, public string $password)
    {
    }
}