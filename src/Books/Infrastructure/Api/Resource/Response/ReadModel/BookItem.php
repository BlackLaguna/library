<?php

declare(strict_types=1);

namespace Books\Infrastructure\Api\Resource\Response\ReadModel;

use Symfony\Component\Serializer\Attribute\Groups;

class BookItem
{
    public function __construct(
        #[Groups('item')]
        public readonly string $id,
        #[Groups('item')]
        public readonly string $name,
        /** @var Reservation[] */
        #[Groups('item')]
        public array $reservations,
    ) {
    }
}