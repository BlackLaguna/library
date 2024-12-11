<?php

declare(strict_types=1);

namespace Auth\Infrastructure\Api\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Auth\Application\CQRS\Command\RegisterClientCommand;
use Auth\Application\CQRS\Query\GetClientTokenForUserQuery;
use Auth\Domain\Client\ClientToken as DomainClientToken;
use Auth\Infrastructure\Api\Resource\Request\RegisterRequest;
use Auth\Infrastructure\Api\Resource\Response\ClientToken;
use SharedKernel\Application\Bus\QueryBus;
use Symfony\Component\Messenger\MessageBusInterface;
use UnexpectedValueException;

final readonly class RegisterProcessor implements ProcessorInterface
{
    public function __construct(
        private MessageBusInterface $commandBus,
        private QueryBus $queryBus,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): ClientToken
    {
        if (!($data instanceof RegisterRequest)) {
            throw new UnexpectedValueException();
        }

        $this->commandBus->dispatch(new RegisterClientCommand(
            email: $data->email,
            password: $data->password,
            name: $data->name,
        ));

        /** @var DomainClientToken $clientToken */
        $clientToken = $this->queryBus->dispatch(new GetClientTokenForUserQuery(
            email: $data->email,
            password: $data->password,
        ));

        return new ClientToken($clientToken->token);
    }
}