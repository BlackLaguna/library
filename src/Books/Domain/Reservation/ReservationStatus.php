<?php

declare(strict_types=1);

namespace Books\Domain\Reservation;

enum ReservationStatus: string
{
    case NEW = 'NEW';
    case CANCELED = 'CANCELED';
    case ACTIVE = 'ACTIVE';
    case FINISHED = 'FINISHED';
}