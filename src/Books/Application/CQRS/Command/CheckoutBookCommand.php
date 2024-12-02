<?php

declare(strict_types=1);

namespace Books\Application\CQRS\Command;

final readonly class CheckoutBookCommand
{
    public function __construct(
        public string $bookId,
        public string $clientId,
    ) {
    }
}