<?php

declare(strict_types=1);

namespace Auth\Infrastructure\Api\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use Auth\Infrastructure\Api\Resource\ClientResource;
use Symfony\Bundle\SecurityBundle\Security;

final readonly class ClientProvider implements ProviderInterface
{
    public function __construct(
        private Security $security,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $client = $this->security->getUser();

        return new ClientResource(
            email: $client->getUserIdentifier(),
            name: $client->getName()
        );
    }
}