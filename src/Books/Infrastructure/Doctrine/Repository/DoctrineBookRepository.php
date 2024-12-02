<?php

declare(strict_types=1);

namespace Books\Infrastructure\Doctrine\Repository;

use Books\Domain\Book;
use Books\Domain\Book\Author;
use Books\Domain\Book\AvailableQuantity;
use Books\Domain\Book\BookId;
use Books\Domain\Book\Description;
use Books\Domain\Book\Name;
use Books\Domain\Book\TotalQuantity;
use Books\Domain\Client\ClientId;
use Books\Domain\Repository\BookRepository;
use Books\Domain\Reservation\ReservationId;
use Books\Domain\Reservation\ReservationDateTo;
use Books\Domain\Reservation\ReservationDateFrom;
use Books\Domain\Reservation;
use Books\Domain\Reservation\ReservationStatus;
use Books\Infrastructure\Doctrine\Entity\Book as BookEntity;
use Books\Infrastructure\Doctrine\Entity\Reservation as ReservationEntity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final readonly class DoctrineBookRepository implements BookRepository
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function findById(BookId $bookId): Book
    {
        $bookEntity = $this->entityManager->find(BookEntity::class, (string) $bookId->uuid);

        if (null === $bookEntity) {
            throw new NotFoundHttpException();
        }

        $this->entityManager->clear();

        $reservations = array_map(static fn (ReservationEntity $reservationEntity) => new Reservation(
                id: ReservationId::fromUuid($reservationEntity->getId()),
                bookId: BookId::createFromUuid($reservationEntity->getBookId()),
                dateFrom: ReservationDateFrom::fromInt($reservationEntity->getDateFrom()->getTimestamp()),
                dateTo: ReservationDateTo::fromInt($reservationEntity->getDateTo()->getTimestamp()),
                clientId: ClientId::fromUuid($reservationEntity->getClientId()),
                status: ReservationStatus::from($reservationEntity->getStatus()),
        ), $bookEntity->getReservations()->toArray());

        return new Book(
            ...$reservations,
            id: BookId::createFromUuid($bookEntity->getId()),
            name: Name::fromString($bookEntity->getName()),
            description: Description::fromString($bookEntity->getDescription()),
            availableQuantity: AvailableQuantity::formInt($bookEntity->getAvailableQuantity()),
            totalQuantity: TotalQuantity::fromInt($bookEntity->getTotalQuantity()),
            author: Author::fromString($bookEntity->getAuthorFirstName(), $bookEntity->getAuthorLastName()),
        );
    }

    public function save(Book $book): void
    {
        $bookEntity = BookEntity::createFromDomainBook($book);
        $this->entityManager->persist($bookEntity);
        $this->entityManager->flush();
    }

    public function update(Book $book): void
    {
        $bookEntity = $this->entityManager->find(BookEntity::class, (string) $book->getId()->uuid);

        if (null === $bookEntity) {
            throw new NotFoundHttpException();
        }

        $bookEntity->updateFromDomainBook($book);

        $this->entityManager->flush();
    }
}