<?php

declare(strict_types=1);

namespace Auth\Domain\Exception;

class InvalidEmailException extends \Exception
{
    public function __construct()
    {
        parent::__construct("Invalid email address");
    }
}