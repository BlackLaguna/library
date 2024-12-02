<?php

declare(strict_types=1);

namespace Books\Domain\Repository;

use Books\Domain\Book;
use Books\Domain\Book\BookId;

interface BookRepository
{
    function findById(BookId $bookId): Book;

    function save(Book $book): void;

    function update(Book $book): void;
}