<?php

declare(strict_types=1);

namespace Books\Application\CQRS\Command;

readonly class CreateBookCommand
{
    public function __construct(
        public string $name,
        public string $authorFirstName,
        public string $authorLastName,
        public string $description,
        public int $totalQuantity,
    ) {
    }
}