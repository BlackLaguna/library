<?php

declare(strict_types=1);

namespace Auth\Domain\Exception;

final class TokenExpireException extends \Exception
{
    public function __construct()
    {
        parent::__construct("Token has expired");
    }
}