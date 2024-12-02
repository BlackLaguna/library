<?php

declare(strict_types=1);

namespace Books\Infrastructure\Api\Resource\Request\Dto;

final readonly class Author
{
    public function __construct(public string $firstName, public string $lastName)
    {
    }
}