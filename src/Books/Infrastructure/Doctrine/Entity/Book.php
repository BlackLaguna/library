<?php

declare(strict_types=1);

namespace Books\Infrastructure\Doctrine\Entity;

use Books\Domain\Book as DomainBook;
use Books\Domain\Reservation as DomainReservation;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ReadableCollection;
use Doctrine\ORM\Mapping as ORM;
use SharedKernel\Infrastructure\Doctrine\Entity\BookInterface;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\Table(name: 'books')]
class Book implements BookInterface
{
    public function __construct(
        #[ORM\Id]
        #[ORM\Column(type: 'uuid')]
        private Uuid $uuid,
        #[ORM\Column(type: 'string', length: 255, nullable: false)]
        private string $name,
        #[ORM\Column(type: 'text', nullable: false)]
        private string $description,
        #[ORM\Column(type: 'smallint', nullable: false)]
        private int $totalQuantity,
        #[ORM\Column(type: 'smallint', nullable: false)]
        private int $availableQuantity,
        #[ORM\Column(type: 'string', length: 255, nullable: false)]
        private string $authorFirstName,
        #[ORM\Column(type: 'string', length: 255, nullable: false)]
        private string $authorLastName,
        #[ORM\OneToMany(mappedBy: 'bookId', targetEntity: Reservation::class, cascade: ['PERSIST', 'REMOVE'], fetch: 'EAGER', indexBy: 'uuid')]
        private Collection $reservations = new ArrayCollection(),
    ) {
    }

    public static function createFromDomainBook(DomainBook $book): self
    {
        return new self(
            uuid: $book->getId()->uuid,
            name: $book->getName()->name,
            description: $book->getDescription()->description,
            totalQuantity: $book->getTotalQuantity()->totalQuantity,
            availableQuantity: $book->getAvailableQuantity()->availableQuantity,
            authorFirstName: $book->getAuthor()->firstName,
            authorLastName: $book->getAuthor()->lastName,
            reservations: new ArrayCollection(array_map(
                static fn (DomainReservation $reservation) => Reservation::createFromDomainEntity($reservation),
                $book->getReservations(),
            )),
        );
    }

    public function updateFromDomainBook(DomainBook $book): void
    {
        $this->uuid = $book->getId()->uuid;
        $this->name = $book->getName()->name;
        $this->description = $book->getDescription()->description;
        $this->totalQuantity = $book->getTotalQuantity()->totalQuantity;
        $this->availableQuantity = $book->getAvailableQuantity()->availableQuantity;
        $this->authorFirstName = $book->getAuthor()->firstName;
        $this->authorLastName = $book->getAuthor()->lastName;
        $this->reservations = new ArrayCollection(array_map(
            static fn (DomainReservation $reservation) => Reservation::createFromDomainEntity($reservation),
            $book->getReservations(),
        ));
    }

    public function getId(): Uuid
    {
        return $this->uuid;
    }

    public function getAuthorFirstName(): string
    {
        return $this->authorFirstName;
    }

    public function getAuthorLastName(): string
    {
        return $this->authorLastName;
    }

    public function getTotalQuantity(): int
    {
        return $this->totalQuantity;
    }

    public function getAvailableQuantity(): int
    {
        return $this->availableQuantity;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    /** @return ReadableCollection<int, Reservation> */
    public function getReservations(): ReadableCollection
    {
        return $this->reservations;
    }
}