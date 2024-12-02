<?php

declare(strict_types=1);

namespace Tests\Unit\Books\Domain;

use Books\Domain\Book;
use Books\Domain\Book\Author;
use Books\Domain\Book\AvailableQuantity;
use Books\Domain\Book\Description;
use Books\Domain\Book\Name;
use Books\Domain\Book\TotalQuantity;
use Books\Domain\Client\ClientId;
use Books\Domain\Exception\BookIsUnavailable;
use Books\Domain\Exception\ClientDontHaveReservation;
use Books\Domain\Reservation\ReservationDateFrom;
use Books\Domain\Reservation\ReservationDateTo;
use Books\Domain\Reservation\ReservationStatus;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class BookTest extends TestCase
{
    public function testCrateNew(): void
    {
        $name = Name::fromString('testName');
        $description = Description::fromString('Test Description');
        $totalQuantity = TotalQuantity::fromInt(10);
        $author = Author::fromString('testFirstName', 'testLastName');

        $book = Book::createNew($name, $description, $totalQuantity, $author);

        static::assertTrue($book->getName()->equals($name));
        static::assertTrue($book->getDescription()->equals($description));
        static::assertTrue($book->getTotalQuantity()->equals($totalQuantity));
        static::assertTrue($book->getAvailableQuantity()->equals(AvailableQuantity::formInt(10)));
        static::assertTrue($book->getAuthor()->equals($author));
    }

    public function testUpdateDescription(): void
    {
        $newDescription = 'New description';
        $totalQuantity = 10;
        $book = $this->getBookInstance(totalQuantity: $totalQuantity);

        $book->updateDescription(Description::fromString($newDescription));

        static::assertEquals($newDescription, $book->getDescription()->description);
    }

    public function testMarkUnavailable(): void
    {
        $totalQuantity = 10;
        $book = $this->getBookInstance(totalQuantity: $totalQuantity);

        $book->markAsUnavailable();

        static::assertEquals(0, $book->getTotalQuantity()->totalQuantity);
        static::assertEquals(0, $book->getAvailableQuantity()->availableQuantity);
    }

    public function testRserveBook(): void
    {
        $totalQuantity = 10;
        $book = $this->getBookInstance(totalQuantity: $totalQuantity);

        $book->reserve(
            $reservationDateFrom = ReservationDateFrom::fromInt(1),
            $reservationDateTo = ReservationDateTo::fromInt(2),
            $clientId = ClientId::fromUuid(Uuid::v4()),
        );

        static::assertEquals($totalQuantity - 1, $book->getAvailableQuantity()->availableQuantity);
        static::assertCount(1, $book->getReservations());
        $reservation = $book->getReservations()[0];
        static::assertEquals(ReservationStatus::NEW, $reservation->getStatus());
        static::assertTrue($reservation->getDateFrom()->equals($reservationDateFrom));
        static::assertTrue($reservation->getDateTo()->equals($reservationDateTo));
        static::assertTrue($reservation->getClientId()->equals($clientId));
    }

    public function testItThrowsExceptionWhenTryToReserveBookWhenItNotAvailable(): void
    {
        $totalQuantity = 0;
        $book = $this->getBookInstance(totalQuantity: $totalQuantity);

        static::expectException(BookIsUnavailable::class);
        $book->reserve(
            ReservationDateFrom::fromInt(1),
            ReservationDateTo::fromInt(2),
            ClientId::fromUuid(Uuid::v4()),
        );
    }

    public function testAddBookStock(): void
    {
        $totalQuantity = 1;
        $book = $this->getBookInstance(totalQuantity: $totalQuantity);

        $book->addStock();

        static::assertEquals(2, $book->getTotalQuantity()->totalQuantity);
        static::assertEquals(2, $book->getAvailableQuantity()->availableQuantity);
    }

    public function testReturnBook(): void
    {
        $totalQuantity = 10;
        $book = $this->getBookInstance(totalQuantity: $totalQuantity);
        $book->reserve(
            ReservationDateFrom::fromInt(1),
            ReservationDateTo::fromInt(2),
            $clientId = ClientId::fromUuid(Uuid::v4()),
        );

        $book->return($clientId);

        static::assertEquals($totalQuantity, $book->getAvailableQuantity()->availableQuantity);
    }

    public function testReturnBookWhenAllBooksInStock(): void
    {
        $totalQuantity = 10;
        $book = $this->getBookInstance(totalQuantity: $totalQuantity);

        static::expectException(ClientDontHaveReservation::class);
        $book->return(ClientId::fromUuid(Uuid::v4()));
    }

    private function getBookInstance(int $totalQuantity, string $description = 'test'): Book
    {
        return Book::createNew(
            Name::fromString('test'),
            Description::fromString($description),
            TotalQuantity::fromInt($totalQuantity),
            Author::fromString('test', 'test')
        );
    }
}
