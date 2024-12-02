<?php

declare(strict_types=1);

namespace Books\Domain\Reservation;

use DateTimeImmutable;

readonly final class ReservationDateTo
{
    private function __construct(public DateTimeImmutable $date)
    {
    }

    public static function fromInt(int $date): self
    {
        return new self(DateTimeImmutable::createFromFormat('U', (string) $date));
    }

    public function equals(self $dateTo): bool
    {
        return $this->date === $dateTo->date;
    }
}