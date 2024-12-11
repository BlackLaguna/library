<?php

declare(strict_types=1);

namespace Books\Domain\Book;

use Symfony\Component\Uid\Uuid;

readonly class BookId
{
    public function __construct(public Uuid $id)
    {
    }

    public static function createFromUuid(Uuid $uuid): self
    {
        return new self($uuid);
    }

    public function equals(BookId $id): bool
    {
        return $this->id->equals($id);
    }
}