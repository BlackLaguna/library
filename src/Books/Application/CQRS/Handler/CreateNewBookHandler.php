<?php

declare(strict_types=1);

namespace Books\Application\CQRS\Handler;

use Books\Application\CQRS\Command\CreateBookCommand;
use Books\Domain\Book;
use Books\Domain\Book\Author;
use Books\Domain\Book\Description;
use Books\Domain\Book\Name;
use Books\Domain\Book\TotalQuantity;
use Books\Domain\Repository\BookRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class CreateNewBookHandler
{
    public function __construct(private BookRepository $bookRepository)
    {
    }

    public function __invoke(CreateBookCommand $command): void
    {
        $book = Book::createNew(
            Name::fromString($command->name),
            Description::fromString($command->description),
            TotalQuantity::fromInt($command->totalQuantity),
            Author::fromString($command->authorFirstName, $command->authorLastName),
        );
        $this->bookRepository->save($book);
    }
}