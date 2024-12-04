<?php

namespace Auth\Domain\Services;

use Auth\Domain\Client\ClientEmail;
use Auth\Domain\Client\ClientId;

interface IsClientExistService
{
    public function isClientExist(ClientEmail $clientEmail, ClientEmail $clientEmail): bool;
}