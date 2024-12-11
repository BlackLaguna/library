<?php

namespace Auth\Domain;

use Auth\Domain\Client\ClientEmail;

interface ClientRepository
{
    public function getById(ClientEmail $clientEmail): Client;
}