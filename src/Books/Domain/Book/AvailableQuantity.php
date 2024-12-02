<?php

declare(strict_types=1);

namespace Books\Domain\Book;

readonly class AvailableQuantity
{
    private function __construct(public int $availableQuantity)
    {
    }

    public static function formInt(int $availableQuantity): self
    {
        return new self($availableQuantity);
    }

    public function equals(self $availableQuantity): bool
    {
        return $this->availableQuantity === $availableQuantity->availableQuantity;
    }
}