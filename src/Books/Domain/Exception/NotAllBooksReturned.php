<?php

declare(strict_types=1);

namespace Books\Domain\Exception;

use Exception;

final class NotAllBooksReturned extends Exception
{
    public function __construct()
    {
        parent::__construct('Books returned is not all');
    }
}