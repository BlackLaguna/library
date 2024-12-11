<?php

declare(strict_types=1);

namespace Books\Application\CQRS\Handler;

use Books\Application\CQRS\Command\ReturnBookCommand;
use Books\Domain\Book\BookId;
use Books\Domain\Client\ClientId;
use Books\Domain\Exception\AllBooksAlreadyInTheStock;
use Books\Domain\Exception\ClientDontHaveReservation;
use Books\Domain\Repository\BookRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Uid\Uuid;

#[AsMessageHandler]
readonly class ReturnBookHandler
{
    public function __construct(private BookRepository $bookRepository)
    {
    }

    /**
     * @throws ClientDontHaveReservation
     * @throws AllBooksAlreadyInTheStock
     */
    public function __invoke(ReturnBookCommand $command): void
    {
        $book = $this->bookRepository->findById(new BookId(Uuid::fromString($command->bookId)));
        $book->return(ClientId::fromString($command->clientId));
        $this->bookRepository->update($book);
    }
}