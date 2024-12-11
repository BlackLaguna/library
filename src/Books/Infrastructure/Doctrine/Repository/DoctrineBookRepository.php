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
use Symfony\Component\Uid\Uuid;

final readonly class DoctrineBookRepository implements BookRepository
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function findById(BookId $bookId): Book
    {
        $bookEntity = $this->entityManager->find(BookEntity::class, (string) $bookId->id);
        $reservations = $this->entityManager->createQueryBuilder()
            ->select('reservation')
            ->from(ReservationEntity::class, 'reservation')
            ->where('reservation.book = :book')
            ->andWhere('reservation.status IN (:reservationStatuses)')
            ->setParameters([
                'book' => $bookEntity,
                'reservationStatuses' => [ReservationStatus::NEW, ReservationStatus::ACTIVE],
            ])
            ->getQuery()
            ->execute();

        if (null === $bookEntity) {
            throw new NotFoundHttpException();
        }

        $reservations = array_map(static fn (ReservationEntity $reservationEntity) => new Reservation(
                id: ReservationId::fromUuid($reservationEntity->getId()),
                bookId: BookId::createFromUuid(Uuid::fromString($reservationEntity->getBook()->getId())),
                dateFrom: ReservationDateFrom::fromInt($reservationEntity->getDateFrom()->getTimestamp()),
                dateTo: ReservationDateTo::fromInt($reservationEntity->getDateTo()->getTimestamp()),
                clientId: ClientId::fromString($reservationEntity->getClient()->getId()),
                status: ReservationStatus::from($reservationEntity->getStatus()),
        ), $reservations);

        return new Book(
            id: BookId::createFromUuid(Uuid::fromString($bookEntity->getId())),
            name: Name::fromString($bookEntity->getName()),
            description: Description::fromString($bookEntity->getDescription()),
            availableQuantity: AvailableQuantity::formInt($bookEntity->getAvailableQuantity()),
            totalQuantity: TotalQuantity::fromInt($bookEntity->getTotalQuantity()),
            author: Author::fromString($bookEntity->getAuthorFirstName(), $bookEntity->getAuthorLastName()),
            reservations: $reservations,
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
        $bookEntity = $this->entityManager->find(BookEntity::class, (string) $book->getId()->id);

        if (null === $bookEntity) {
            throw new NotFoundHttpException();
        }

        $this->entityManager->beginTransaction();

        $bookEntity->updateFromDomainBook($book);

        $this->entityManager->createQueryBuilder()
            ->update(BookEntity::class, 'book')
            ->set('book.name', ':name')
            ->set('book.description', ':description')
            ->set('book.availableQuantity', ':availableQuantity')
            ->set('book.totalQuantity', ':totalQuantity')
            ->set('book.authorFirstName', ':authorFirstName')
            ->set('book.authorLastName', ':authorLastName')
            ->where('book.id = :id')
            ->setParameters([
                'id' => (string) $bookEntity->getId(),
                'name' => $bookEntity->getName(),
                'description' => $bookEntity->getDescription(),
                'availableQuantity' => $bookEntity->getAvailableQuantity(),
                'totalQuantity' => $bookEntity->getTotalQuantity(),
                'authorFirstName' => $bookEntity->getAuthorFirstName(),
                'authorLastName' => $bookEntity->getAuthorLastName(),
            ])
            ->getQuery()
            ->execute();

        foreach ($book->getReservations() as $reservation) {
            $statement =
                'INSERT INTO reservations (id, book_id, client_id, status, date_from, date_to)
                VALUES (:id, :book_id, :client_id, :status, :date_from, :date_to)
                ON CONFLICT (id) DO UPDATE SET
                book_id = EXCLUDED.book_id,
                client_id = EXCLUDED.client_id,
                status = EXCLUDED.status,
                date_from = EXCLUDED.date_from,
                date_to = EXCLUDED.date_to;'
            ;
            $this->entityManager->getConnection()->executeStatement($statement, [
                'id' => (string) $reservation->getId()->uuid,
                'book_id' => (string) $reservation->getBookId()->id,
                'client_id' => $reservation->getClientId()->email,
                'status' => $reservation->getStatus()->value,
                'date_from' => $reservation->getDateFrom()->date->format('Y-m-d H:i:s'),
                'date_to' => $reservation->getDateTo()->date->format('Y-m-d H:i:s'),
            ]);
        }

        $this->entityManager->commit();
    }
}