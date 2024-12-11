<?php

declare(strict_types=1);

namespace Auth\Infrastructure\Api\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Auth\Application\CQRS\Query\GetClientTokenForUserQuery;
use Auth\Domain\Client\ClientToken as DomainClientToken;
use Auth\Infrastructure\Api\Resource\Request\LoginRequest;
use Auth\Infrastructure\Api\Resource\Response\ClientToken;
use SharedKernel\Application\Bus\QueryBus;
use UnexpectedValueException;

final readonly class LoginProcessor implements ProcessorInterface
{
    public function __construct(private QueryBus $queryBus)
    {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): ClientToken
    {
        if (!($data instanceof LoginRequest)) {
            throw new UnexpectedValueException();
        }

        /** @var DomainClientToken $clientToken */
        $clientToken = $this->queryBus->dispatch(new GetClientTokenForUserQuery(
            email: $data->email,
            password: $data->password,
        ));

        return new ClientToken($clientToken->token);
    }
}