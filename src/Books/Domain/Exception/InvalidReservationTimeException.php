<?php

declare(strict_types=1);

namespace Books\Domain\Exception;

use Exception;

final class InvalidReservationTimeException extends Exception
{
    public function __construct()
    {
        parent::__construct('Invalid reservation time');
    }
}