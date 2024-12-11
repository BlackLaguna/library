<?php

declare(strict_types=1);

namespace Auth\Domain\Service;

use Auth\Domain\Client\ClientEmail;

interface IsClientExistService
{
    public function isClientExist(ClientEmail $clientEmail): bool;
}