<?php

declare(strict_types=1);

namespace Books\Infrastructure\Api\Resource\Request;

use Books\Infrastructure\Api\Resource\Request\Dto\Author;

final readonly class CreateBookRequest
{
    public function __construct(
        public string $name,
        public Author $author,
        public int $totalQuantity,
        public string $description,
    ) {
    }
}