<?php

declare(strict_types=1);

namespace Books\Application\CQRS\Handler;

use Books\Application\CQRS\Command\MakeBookUnavailableCommand;
use Books\Domain\Book\BookId;
use Books\Domain\Repository\BookRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Uid\Uuid;

#[AsMessageHandler]
readonly class MakeBookUnavailableHandler
{
    public function __construct(private BookRepository $bookRepository)
    {
    }

    public function __invoke(MakeBookUnavailableCommand $command): void
    {
        $book = $this->bookRepository->findById(new BookId(Uuid::fromString($command->bookId)));
        $book->markAsUnavailable();
        $this->bookRepository->update($book);
    }
}