<?php

namespace Auth\Domain\Services;

use Auth\Domain\Client\ClientId;

interface IsClientExistService
{
    public function isClientExist(ClientId $clientId): bool;
}