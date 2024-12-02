<?php

declare(strict_types=1);

namespace Books\Application\CQRS\Handler;

use Books\Application\CQRS\Command\ReserveBookCommand;
use Books\Domain\Book\BookId;
use Books\Domain\Client\ClientId;
use Books\Domain\Repository\BookRepository;
use Books\Domain\Reservation\ReservationDateFrom;
use Books\Domain\Reservation\ReservationDateTo;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Uid\Uuid;

#[AsMessageHandler]
readonly class ReserveBookHandler
{
    public function __construct(private BookRepository $bookRepository)
    {
    }

    public function __invoke(ReserveBookCommand $command): void
    {
        $book = $this->bookRepository->findById(new BookId(Uuid::fromString($command->bookId)));
        $book->reserve(
            ReservationDateFrom::fromInt($command->reserveFrom),
            ReservationDateTo::fromInt($command->reserveTo),
            ClientId::fromUuid(Uuid::fromString($command->clientId)),
        );
        $this->bookRepository->update($book);
    }
}