<?php

declare(strict_types=1);

namespace Books\Infrastructure\Api\Resource\Response\ReadModel;

use DateTimeImmutable;
use Symfony\Component\Serializer\Attribute\Groups;

final readonly class Reservation
{
    public function __construct(
        #[Groups('item')]
        public string $status,
        #[Groups('item')]
        public DateTimeImmutable $dateFrom,
        #[Groups('item')]
        public DateTimeImmutable $dateTo,
    ) {
    }
}