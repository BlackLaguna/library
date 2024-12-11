<?php

declare(strict_types=1);

namespace Books\Infrastructure\Doctrine\Entity;

use ApiPlatform\Metadata\ApiProperty;
use Books\Domain\Book as DomainBook;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ReadableCollection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'books')]
class Book
{
    public function __construct(
        #[ORM\Id]
        #[ORM\Column(type: 'uuid')]
        #[ApiProperty(identifier: true)]
        private string $id,
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
        #[ORM\OneToMany(mappedBy: 'book', targetEntity: Reservation::class, cascade: ['REMOVE'], fetch: 'EXTRA_LAZY', indexBy: 'id')]
        private Collection $reservations = new ArrayCollection(),
    ) {
    }

    public static function createFromDomainBook(DomainBook $book): self
    {
        return new self(
            id: (string) $book->getId()->id,
            name: $book->getName()->name,
            description: $book->getDescription()->description,
            totalQuantity: $book->getTotalQuantity()->totalQuantity,
            availableQuantity: $book->getAvailableQuantity()->availableQuantity,
            authorFirstName: $book->getAuthor()->firstName,
            authorLastName: $book->getAuthor()->lastName,
        );
    }

    public function updateFromDomainBook(DomainBook $book): void
    {
        $this->id = (string) $book->getId()->id;
        $this->name = $book->getName()->name;
        $this->description = $book->getDescription()->description;
        $this->totalQuantity = $book->getTotalQuantity()->totalQuantity;
        $this->availableQuantity = $book->getAvailableQuantity()->availableQuantity;
        $this->authorFirstName = $book->getAuthor()->firstName;
        $this->authorLastName = $book->getAuthor()->lastName;
    }

    public function setReservations(Collection $reservations): void
    {
        $this->reservations = $reservations;
    }

    public function getId(): string
    {
        return $this->id;
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