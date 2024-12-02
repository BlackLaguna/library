<?php

declare(strict_types=1);

namespace Books\Domain;

use Books\Domain\Book\BookId;
use Books\Domain\Client\ClientId;
use Books\Domain\Exception\InvalidReservationDateRange;
use Books\Domain\Reservation\ReservationDateFrom;
use Books\Domain\Reservation\ReservationDateTo;
use Books\Domain\Reservation\ReservationId;
use Books\Domain\Reservation\ReservationStatus;
use Symfony\Component\Uid\Uuid;

class Reservation
{
    public function __construct(
        private ReservationId $id,
        private BookId $bookId,
        private ReservationDateFrom $dateFrom,
        private ReservationDateTo $dateTo,
        private ClientId $clientId,
        private ReservationStatus $status,
    ) {
    }

    /** @throws InvalidReservationDateRange */
    public static function createNew(
        BookId $bookId,
        ReservationDateFrom $reserveFrom,
        ReservationDateTo $reserveTo,
        ClientId $clientId,
    ): self {
        if ($reserveFrom->date >= $reserveTo->date) {
            throw new InvalidReservationDateRange();
        }

        $id = ReservationId::fromUuid(Uuid::v4());

        return new self($id, $bookId, $reserveFrom, $reserveTo, $clientId, ReservationStatus::NEW);
    }

    public function changeStatus(ReservationStatus $status): void
    {
        $this->status = $status;
    }

    public function getId(): ReservationId
    {
        return $this->id;
    }

    public function getBookId(): BookId
    {
        return $this->bookId;
    }

    public function getClientId(): ClientId
    {
        return $this->clientId;
    }

    public function getDateFrom(): ReservationDateFrom
    {
        return $this->dateFrom;
    }

    public function getDateTo(): ReservationDateTo
    {
        return $this->dateTo;
    }

    public function getStatus(): ReservationStatus
    {
        return $this->status;
    }
}