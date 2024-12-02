<?php

declare(strict_types=1);

namespace Books\Domain\Book;

readonly class TotalQuantity
{
    private function __construct(public int $totalQuantity)
    {
    }

    public static function fromInt(int $totalQuantity): self
    {
        return new self($totalQuantity);
    }

    public function equals(self $totalQuantity): bool
    {
        return $this->totalQuantity === $totalQuantity->totalQuantity;
    }
}