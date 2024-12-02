<?php

declare(strict_types=1);

namespace Books\Application\CQRS\Command;

readonly class ChangeBookDescriptionCommand
{
    public function __construct(
        public string $bookId,
        public string $newDescription,
    ) {
    }
}