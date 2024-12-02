<?php

declare(strict_types=1);

namespace Books\Domain\Reservation;

use Symfony\Component\Uid\Uuid;

final readonly class ReservationId
{
    public function __construct(public Uuid $uuid)
    {
    }

    public static function fromUuid(Uuid $uuid): self
    {
        return new self($uuid);
    }
}