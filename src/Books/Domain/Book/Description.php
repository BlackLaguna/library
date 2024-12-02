<?php

declare(strict_types=1);

namespace Books\Domain\Book;

readonly class Description
{
    private function __construct(public string $description)
    {
    }

    public static function fromString(string $description): self
    {
        return new self($description);
    }

    public function equals(self $description): bool
    {
        return $this->description === $description->description;
    }
}