<?php

declare(strict_types=1);

namespace Books\Domain\Reservation;

use DateTimeImmutable;

readonly final class ReservationDateFrom
{
    private function __construct(public DateTimeImmutable $date)
    {
    }

    public static function fromInt(int $date): self
    {
        return new self(DateTimeImmutable::createFromFormat('U', (string) $date));
    }

    public function equals(self $dateFrom): bool
    {
        return $this->date === $dateFrom->date;
    }
}