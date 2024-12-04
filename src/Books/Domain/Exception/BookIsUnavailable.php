<?php

declare(strict_types=1);

namespace Books\Domain\Exception;

use Exception;

final class BookIsUnavailable extends Exception
{
    public function __construct()
    {
        parent::__construct("Book is unavailable");
    }
}