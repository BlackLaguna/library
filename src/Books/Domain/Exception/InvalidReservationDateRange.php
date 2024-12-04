<?php

declare(strict_types=1);

namespace Books\Domain\Exception;

use Exception;

final class InvalidReservationDateRange extends Exception
{
    public function __construct()
    {
        parent::__construct('Reservation date range is invalid');
    }
}