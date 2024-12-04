<?php

declare(strict_types=1);

namespace Books\Domain\Exception;

use Exception;

final class ClientDontHaveReservation extends Exception
{
    public function __construct()
    {
        parent::__construct("Client don't have a reservation");
    }
}