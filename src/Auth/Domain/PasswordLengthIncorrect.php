<?php

declare(strict_types=1);

namespace Auth\Domain;

class PasswordLengthIncorrect extends \Exception
{
    public function __construct() {
        parent::__construct("Password length should be at least 6 characters.");
    }
}