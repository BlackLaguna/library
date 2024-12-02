<?php

declare(strict_types=1);

namespace Books\Infrastructure\Api\Resource\Request;

final readonly class ChangeBookDescriptionRequest
{
    public function __construct(
        public string $newDescription,
    ) {
    }
}