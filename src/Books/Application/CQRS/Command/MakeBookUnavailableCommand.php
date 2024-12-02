<?php

declare(strict_types=1);

namespace Books\Application\CQRS\Command;

final readonly class MakeBookUnavailableCommand
{
    public function __construct(
        public string $bookId,
    ) {
    }
}