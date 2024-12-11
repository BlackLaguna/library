<?php

declare(strict_types=1);

namespace Auth\Domain\Exception;

final class PasswordMismatched extends \Exception
{
    public function __construct()
    {
        parent::__construct("Passwords do not match");
    }
}