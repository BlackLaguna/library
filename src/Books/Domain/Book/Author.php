<?php

declare(strict_types=1);

namespace Books\Domain\Book;

readonly class Author
{
    private function __construct(
        public string $firstName,
        public string $lastName,
    ) {
    }

    public static function fromString(string $firstName, string $lastName): self
    {
        return new self($firstName, $lastName);
    }

    public function equals(self $author): bool
    {
        return $this->firstName === $author->firstName && $this->lastName === $author->lastName;
    }
}