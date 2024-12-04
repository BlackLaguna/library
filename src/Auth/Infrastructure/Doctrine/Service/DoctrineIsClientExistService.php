<?php

declare(strict_types=1);

namespace Auth\Infrastructure\Doctrine\Service;

use Auth\Domain\Client\ClientId;
use Auth\Domain\Services\IsClientExistService;
use Auth\Infrastructure\Doctrine\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;

final class DoctrineIsClientExistService implements IsClientExistService
{
    public function __construct(private EntityManagerInterface $entityManager)
    {

    }
    public function isClientExist(ClientId $clientId): bool
    {
        // TODO
    }
}