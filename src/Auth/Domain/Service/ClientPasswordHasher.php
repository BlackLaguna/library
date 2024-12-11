<?php

declare(strict_types=1);

namespace Auth\Domain\Service;

use Auth\Domain\Client\ClientPassword;

interface ClientPasswordHasher
{
    public function hash(ClientPassword $password): ClientPassword;
}