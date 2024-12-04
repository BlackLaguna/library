<?php

declare(strict_types=1);

namespace Books\Domain\Exception;

use Exception;

final class ClientAlreadyHasReservation extends Exception
{
    public function __construct()
    {
        parent::__construct('Client already has reservation');
    }
}