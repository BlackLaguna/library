<?php

declare(strict_types=1);

namespace Auth\Domain\Exception;

final class ClientAlreadyExistsException extends \Exception
{
    public function __construct()
    {
        parent::__construct('Client already exists!');
    }
}