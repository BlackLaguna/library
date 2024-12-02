<?php

declare(strict_types=1);

namespace Books\Infrastructure\Doctrine\Entity;

use Books\Domain\Reservation as DomainReservation;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use SharedKernel\Infrastructure\Doctrine\Entity\BookInterface;
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
        #[ORM\ManyToOne(targetEntity: BookInterface::class, inversedBy: 'reservations')]
        #[ORM\JoinColumn(name: 'book_id', referencedColumnName: 'uuid', nullable: false)]
        private Uuid $bookId,
        #[ORM\Column(type: 'text', length: 32)]
        private string $status,
        #[ORM\Column(type: 'datetime', nullable: false)]
        private DateTimeImmutable $dateFrom,
        #[ORM\Column(type: 'datetime', nullable: false)]
        private DateTimeImmutable $dateTo,
    ) {
    }

    public static function createFromDomainEntity(DomainReservation $reservation): self
    {
        return new self(
            uuid: $reservation->getId()->uuid,
            client: new Client($reservation->getClientId()->uuid),
            bookId: $reservation->getBookId()->uuid,
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

    public function getBookId(): Uuid
    {
        return $this->bookId;
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