<?php

declare(strict_types=1);

namespace Books\Infrastructure\Api\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use Books\Infrastructure\Api\Resource\Book as BookResource;
use Books\Infrastructure\Api\Resource\Response\ReadModel\BookItem;
use Books\Infrastructure\Api\Resource\Response\ReadModel\Reservation as ReservationResponseReadModel;
use Books\Infrastructure\Doctrine\Entity\Book;
use Books\Infrastructure\Doctrine\Entity\Reservation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

final readonly class GetBookItemProvider implements ProviderInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private Security $security,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $bookId = $uriVariables['id'];
        $clientId = $this->security->getUser()->getUserIdentifier();

        $book = $this->entityManager->find(Book::class, $bookId);
        $clientReservations = $book->getReservations()->filter(
            function (Reservation $reservation) use ($clientId) {
                if ($reservation->getClient()->getId() === $clientId) {
                    return $reservation;
                }

                return null;
            }
        );

        return new BookItem(
            id: $book->getId(),
            name: $book->getName(),
            reservations: array_map(
                static fn (Reservation $reservation) => new ReservationResponseReadModel(
                    status: $reservation->getStatus(),
                    dateFrom: $reservation->getDateFrom(),
                    dateTo: $reservation->getDateTo(),
                ),
                $clientReservations->toArray()
            ),
        );
    }
}