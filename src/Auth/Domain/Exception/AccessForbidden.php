<?php

declare(strict_types=1);

namespace Auth\Domain\Exception;

final class AccessForbidden extends \Exception
{
    public function __construct()
    {
        parent::__construct('Forbidden for current roles');
    }
}