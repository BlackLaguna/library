<?php

declare(strict_types=1);

namespace Books\Domain\Book;

readonly class Name
{
    private function __construct(public string $name)
    {
    }

    public static function fromString(string $name): self
    {
        return new self($name);
    }

    public function equals(self $name): bool
    {
        return $this->name === $name->name;
    }
}