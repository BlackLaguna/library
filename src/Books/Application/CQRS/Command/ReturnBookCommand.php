<?php

declare(strict_types=1);

namespace Books\Application\CQRS\Command;

class ReturnBookCommand
{
    public function __construct(
        public readonly string $bookId,
    ) {
    }
}