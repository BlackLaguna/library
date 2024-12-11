<?php

declare(strict_types=1);

namespace Books\Application\CQRS\Handler;

use Books\Application\CQRS\Command\ReserveBookCommand;
use Books\Domain\Book\BookId;
use Books\Domain\Client\ClientId;
use Books\Domain\Exception\BookIsUnavailable;
use Books\Domain\Exception\ClientAlreadyHasReservation;
use Books\Domain\Exception\InvalidReservationDateRange;
use Books\Domain\Exception\InvalidReservationTimeException;
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

    /**
     * @throws BookIsUnavailable
     * @throws InvalidReservationDateRange
     * @throws ClientAlreadyHasReservation
     * @throws InvalidReservationTimeException
     */
    public function __invoke(ReserveBookCommand $command): void
    {
        $book = $this->bookRepository->findById(BookId::createFromUuid(Uuid::fromString($command->bookId)));
        $book->reserve(
            ReservationDateFrom::fromInt($command->reserveFrom),
            ReservationDateTo::fromInt($command->reserveTo),
            ClientId::fromString($command->clientId),
        );
        $this->bookRepository->update($book);
    }
}