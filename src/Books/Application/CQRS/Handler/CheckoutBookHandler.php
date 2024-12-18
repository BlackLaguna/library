<?php

declare(strict_types=1);

namespace Books\Application\CQRS\Handler;

use Books\Application\CQRS\Command\CheckoutBookCommand;
use Books\Domain\Book\BookId;
use Books\Domain\Client\ClientId;
use Books\Domain\Repository\BookRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Uid\Uuid;

#[AsMessageHandler]
readonly class CheckoutBookHandler
{
    public function __construct(private BookRepository $bookRepository)
    {
    }

    public function __invoke(CheckoutBookCommand $command): void
    {
        $book = $this->bookRepository->findById(new BookId(Uuid::fromString($command->bookId)));
        $book->checkout(ClientId::fromString($command->clientId));
        $this->bookRepository->update($book);
    }
}