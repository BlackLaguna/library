<?php

declare(strict_types=1);

namespace Books\Domain;

use Books\Domain\Book\Author;
use Books\Domain\Book\AvailableQuantity;
use Books\Domain\Book\BookId;
use Books\Domain\Book\Description;
use Books\Domain\Book\Name;
use Books\Domain\Book\TotalQuantity;
use Books\Domain\Client\ClientId;
use Books\Domain\Exception\AllBooksAlreadyInTheStock;
use Books\Domain\Exception\BookIsUnavailable;
use Books\Domain\Exception\ClientAlreadyHasReservation;
use Books\Domain\Exception\ClientDontHaveReservation;
use Books\Domain\Exception\InvalidReservationDateRange;
use Books\Domain\Exception\NotAllBooksReturned;
use Books\Domain\Reservation\ReservationDateFrom;
use Books\Domain\Reservation\ReservationDateTo;
use Books\Domain\Reservation\ReservationStatus;
use Symfony\Component\Uid\Uuid;

class Book
{
    /** @var Reservation[] */
    private array $reservations;

    public function __construct(
        private BookId $id,
        private Name $name,
        private Description $description,
        private AvailableQuantity $availableQuantity,
        private TotalQuantity $totalQuantity,
        private Author $author,
        array $reservations,
    ) {
        $this->reservations = $reservations;
    }

    public static function createNew(
        Name $name,
        Description $description,
        TotalQuantity $totalQuantity,
        Author $author
    ): self {
        $id = BookId::createFromUuid(Uuid::v4());
        $availableQuantity = AvailableQuantity::formInt($totalQuantity->totalQuantity);

        return new self(
            id: $id,
            name: $name,
            description: $description,
            availableQuantity: $availableQuantity,
            totalQuantity: $totalQuantity,
            author: $author,
            reservations: [],
        );
    }

    public function updateDescription(Description $description): void
    {
        $this->description = $description;
    }

    /** @throws NotAllBooksReturned */
    public function markAsUnavailable(): void
    {
        if ($this->totalQuantity->totalQuantity !== $this->availableQuantity->availableQuantity) {
            throw new NotAllBooksReturned();
        }

        $this->totalQuantity = TotalQuantity::fromInt(0);
        $this->availableQuantity = AvailableQuantity::formInt(0);
    }

    public function addStock(int $stockValueFactor = 1): void
    {
        $this->totalQuantity = TotalQuantity::fromInt(
            $this->totalQuantity->totalQuantity + $stockValueFactor
        );
        $this->availableQuantity = AvailableQuantity::formInt(
            $this->availableQuantity->availableQuantity + $stockValueFactor
        );
    }

    /**
     * @throws BookIsUnavailable
     * @throws InvalidReservationDateRange
     * @throws ClientAlreadyHasReservation
     */
    public function reserve(ReservationDateFrom $dateFrom, ReservationDateTo $dateTo, ClientId $clientId): void
    {
        if (0 === $this->availableQuantity->availableQuantity) {
            throw new BookIsUnavailable();
        }

        foreach ($this->reservations as $reservation) {
            if ($reservation->getClientId()->equals($clientId)) {
                throw new ClientAlreadyHasReservation();
            }
        }

        $this->availableQuantity = AvailableQuantity::formInt($this->availableQuantity->availableQuantity - 1);

        $this->reservations[] = Reservation::createNew(
            bookId: $this->id,
            reserveFrom: $dateFrom,
            reserveTo: $dateTo,
            clientId: $clientId,
        );
    }

    /**
     * @throws AllBooksAlreadyInTheStock
     * @throws ClientDontHaveReservation
     */
    public function return(ClientId $clientId): void
    {
        foreach ($this->reservations as $reservation) {
            if ($reservation->getClientId()->equals($clientId)) {
                $clientReservation = $reservation;
            }
        }

        if (!isset($clientReservation)) {
            throw new ClientDontHaveReservation();
        }

        if ($this->totalQuantity->totalQuantity === $this->availableQuantity->availableQuantity) {
            throw new AllBooksAlreadyInTheStock();
        }

        $clientReservation->changeStatus(ReservationStatus::FINISHED);
        $this->availableQuantity = AvailableQuantity::formInt($this->availableQuantity->availableQuantity + 1);
    }

    /**
     * @throws ClientDontHaveReservation
     */
    public function checkout(ClientId $clientId): void
    {
        foreach ($this->reservations as $reservation) {
            if ($reservation->getClientId()->equals($clientId)) {
                $clientReservation = $reservation;
            }
        }

        if (!isset($clientReservation)) {
            throw new ClientDontHaveReservation();
        }

        $clientReservation->changeStatus(ReservationStatus::ACTIVE);
    }

    public function getId(): BookId
    {
        return $this->id;
    }

    public function getDescription(): Description
    {
        return $this->description;
    }

    public function getTotalQuantity(): TotalQuantity
    {
        return $this->totalQuantity;
    }

    public function getAvailableQuantity(): AvailableQuantity
    {
        return $this->availableQuantity;
    }

    public function getAuthor(): Author
    {
        return $this->author;
    }

    public function getName(): Name
    {
        return $this->name;
    }

    /** @return Reservation[] */
    public function getReservations(): array
    {
        return $this->reservations;
    }
}