<?php

declare(strict_types=1);

namespace Books\Infrastructure\Api\Resource;

use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use Books\Infrastructure\Api\Processor\ChangeBookDescriptionProcessor;
use Books\Infrastructure\Api\Processor\CheckoutBookProcessor;
use Books\Infrastructure\Api\Processor\CreateBookProcessor;
use Books\Infrastructure\Api\Processor\MakeBookUnavailableProcessor;
use Books\Infrastructure\Api\Processor\ReserveBookProcessor;
use Books\Infrastructure\Api\Processor\ReturnBookProcessor;
use Books\Infrastructure\Api\Resource\Request\ChangeBookDescriptionRequest;
use Books\Infrastructure\Api\Resource\Request\CheckoutBookRequest;
use Books\Infrastructure\Api\Resource\Request\CreateBookRequest;
use Books\Infrastructure\Api\Resource\Request\Dto\Author;
use Books\Infrastructure\Api\Resource\Request\MakeBookUnavailableRequest;
use Books\Infrastructure\Api\Resource\Request\ReserveBookRequest;
use Books\Infrastructure\Api\Resource\Request\ReturnBookRequest;
use Books\Infrastructure\Doctrine\Entity\Book as BookEntity;

#[ApiResource(stateOptions: new Options(entityClass: BookEntity::class))]
#[Post(input: CreateBookRequest::class, processor: CreateBookProcessor::class)]
#[Post(
    uriTemplate: '/books/{uuid}/unavailable',
    input: MakeBookUnavailableRequest::class,
    processor: MakeBookUnavailableProcessor::class,
)]
#[Post(
    uriTemplate: '/books/{uuid}/return',
    input: ReturnBookRequest::class,
    processor: ReturnBookProcessor::class,
)]
#[Post(
    uriTemplate: '/books/{uuid}/reserve',
    input: ReserveBookRequest::class,
    processor: ReserveBookProcessor::class,
)]
#[Post(
    uriTemplate: '/books/{uuid}/checkout',
    input: CheckoutBookRequest::class,
    processor: CheckoutBookProcessor::class,
)]
#[Put(
    uriTemplate: '/books/{uuid}/description',
    input: ChangeBookDescriptionRequest::class,
    processor: ChangeBookDescriptionProcessor::class,
)]
class Book
{
    public function __construct(
        #[ApiProperty(identifier: true)]
        public readonly string $uuid,
        #[ApiProperty(genId: false)]
        public readonly Author $author,
        public readonly int $totalQuantity,
        public readonly int $availableQuantity,
        public readonly string $description,
    ) {
    }
}