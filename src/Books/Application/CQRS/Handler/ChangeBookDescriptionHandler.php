<?php

declare(strict_types=1);

namespace Books\Application\CQRS\Handler;

use Books\Application\CQRS\Command\ChangeBookDescriptionCommand;
use Books\Domain\Book\BookId;
use Books\Domain\Book\Description;
use Books\Domain\Repository\BookRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Uid\Uuid;

#[AsMessageHandler]
readonly class ChangeBookDescriptionHandler
{
    public function __construct(private BookRepository $bookRepository)
    {
    }

    public function __invoke(ChangeBookDescriptionCommand $command): void
    {
        $book = $this->bookRepository->findById(new BookId(Uuid::fromString($command->bookId)));
        $book->updateDescription(Description::fromString($command->newDescription));
        $this->bookRepository->update($book);
    }
}