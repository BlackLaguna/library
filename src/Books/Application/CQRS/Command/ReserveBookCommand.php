<?php

declare(strict_types=1);

namespace Books\Application\CQRS\Command;

readonly final class ReserveBookCommand
{
    public function __construct(
        public string $bookId,
        public string $clientId,
        public int $reserveFrom,
        public int $reserveTo,
    ) {
    }
}