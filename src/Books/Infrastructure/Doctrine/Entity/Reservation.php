<?php

declare(strict_types=1);

namespace Books\Infrastructure\Doctrine\Entity;

use Books\Domain\Reservation as DomainReservation;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\Table(name: 'reservations')]
class Reservation
{
    public function __construct(
        #[ORM\Id]
        #[ORM\Column(type: 'uuid')]
        private Uuid $uuid,
        #[ORM\ManyToOne(targetEntity: Client::class)]
        #[ORM\JoinColumn(name: 'client_id', referencedColumnName: 'uuid', nullable: false)]
        private Client $client,
        #[ORM\ManyToOne(targetEntity: Book::class, inversedBy: 'reservations')]
        #[ORM\JoinColumn(name: 'book_id', referencedColumnName: 'uuid', nullable: false)]
        private Book $book,
        #[ORM\Column(type: 'text', length: 32)]
        private string $status,
        #[ORM\Column(type: 'datetime_immutable', nullable: false)]
        private DateTimeImmutable $dateFrom,
        #[ORM\Column(type: 'datetime_immutable', nullable: false)]
        private DateTimeImmutable $dateTo,
    ) {
    }

    public static function createFromDomainEntity(DomainReservation $reservation, Client $client, Book $book): self
    {
        return new self(
            uuid: $reservation->getId()->uuid,
            client: $client,
            book: $book,
            status: $reservation->getStatus()->value,
            dateFrom: $reservation->getDateFrom()->date,
            dateTo: $reservation->getDateTo()->date
        );
    }

    public function getId(): Uuid
    {
        return $this->uuid;
    }

    public function getClient(): Client
    {
        return $this->client;
    }

    public function getBook(): Book
    {
        return $this->book;
    }

    public function getDateFrom(): DateTimeImmutable
    {
        return $this->dateFrom;
    }

    public function getDateTo(): DateTimeImmutable
    {
        return $this->dateTo;
    }

    public function getStatus(): string
    {
        return $this->status;
    }
}