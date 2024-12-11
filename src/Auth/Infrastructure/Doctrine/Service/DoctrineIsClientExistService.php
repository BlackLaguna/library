<?php

declare(strict_types=1);

namespace Auth\Infrastructure\Doctrine\Service;

use Auth\Domain\Client\ClientEmail;
use Auth\Domain\ClientRepository;
use Auth\Domain\Service\IsClientExistService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class DoctrineIsClientExistService implements IsClientExistService
{
    public function __construct(private ClientRepository $clientRepository)
    {

    }

    public function isClientExist(ClientEmail $clientEmail): bool
    {
        try {
            $this->clientRepository->getById($clientEmail);
        } catch (NotFoundHttpException) {
            return false;
        }

        return true;
    }
}