<?php

declare(strict_types=1);

namespace Books\Domain\Exception;

use Exception;

final class AllBooksAlreadyInTheStock extends Exception
{
    public function __construct()
    {
        parent::__construct("All books already in the stock");
    }
}