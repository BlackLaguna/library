<?php

declare(strict_types=1);

namespace Books\Infrastructure\Api\Resource;

use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use Books\Infrastructure\Api\Processor\ChangeBookDescriptionProcessor;
use Books\Infrastructure\Api\Processor\CheckoutBookProcessor;
use Books\Infrastructure\Api\Processor\CreateBookProcessor;
use Books\Infrastructure\Api\Processor\GetBookItemProvider;
use Books\Infrastructure\Api\Processor\MakeBookUnavailableProcessor;
use Books\Infrastructure\Api\Processor\ReserveBookProcessor;
use Books\Infrastructure\Api\Processor\ReturnBookProcessor;
use Books\Infrastructure\Api\Resource\Request\ChangeBookDescriptionRequest;
use Books\Infrastructure\Api\Resource\Request\CheckoutBookRequest;
use Books\Infrastructure\Api\Resource\Request\CreateBookRequest;
use Books\Infrastructure\Api\Resource\Request\MakeBookUnavailableRequest;
use Books\Infrastructure\Api\Resource\Request\ReserveBookRequest;
use Books\Infrastructure\Api\Resource\Request\ReturnBookRequest;
use Books\Infrastructure\Api\Resource\Response\ReadModel\BookItem;
use Books\Infrastructure\Api\Resource\Response\ReadModel\Reservation;
use Books\Infrastructure\Doctrine\Entity\Book as BookEntity;
use Symfony\Component\Serializer\Attribute\Groups;

#[ApiResource(
    paginationClientItemsPerPage: true,
    paginationEnabled: true,
    stateOptions: new Options(entityClass: BookEntity::class)
)]
#[Get(
    normalizationContext: ['groups' => ['item']],
    output: BookItem::class,
    provider: GetBookItemProvider::class,
)]
#[GetCollection(
    normalizationContext: ['groups' => ['collection']],
)]
#[Post(
    input: CreateBookRequest::class,
    processor: CreateBookProcessor::class,
)]
#[Post(
    uriTemplate: '/books/{id}/unavailable',
    input: MakeBookUnavailableRequest::class,
    processor: MakeBookUnavailableProcessor::class,
)]
#[Post(
    uriTemplate: '/books/{id}/return',
    input: ReturnBookRequest::class,
    processor: ReturnBookProcessor::class,
)]
#[Post(
    uriTemplate: '/books/{id}/reserve',
    input: ReserveBookRequest::class,
    processor: ReserveBookProcessor::class,
)]
#[Post(
    uriTemplate: '/books/{id}/checkout',
    input: CheckoutBookRequest::class,
    processor: CheckoutBookProcessor::class,
)]
#[Put(
    uriTemplate: '/books/{id}/description',
    input: ChangeBookDescriptionRequest::class,
    processor: ChangeBookDescriptionProcessor::class
)]
class Book
{
    public function __construct(
        #[Groups(['collection', 'item'])]
        #[ApiProperty(identifier: true)]
        public readonly string $id,
        #[Groups(['collection', 'item'])]
        public readonly string $authorFirstName,
        #[Groups(['collection', 'item'])]
        public readonly string $authorLastName,
        #[Groups(['collection', 'item'])]
        public readonly int $availableQuantity,
        #[Groups(['collection', 'item'])]
        public readonly string $description,
        /** @var Reservation[] */
        #[ApiProperty(genId: false)]
        #[Groups('item')]
        public readonly array $reservations,
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }
}