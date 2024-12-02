<?php

declare(strict_types=1);

namespace Books\Infrastructure\Api\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Books\Application\CQRS\Command\ReserveBookCommand;
use Books\Infrastructure\Api\Resource\Request\ReserveBookRequest;
use InvalidArgumentException;
use Symfony\Component\Messenger\MessageBusInterface;

final readonly class ReserveBookProcessor implements ProcessorInterface
{
    public function __construct(private MessageBusInterface $commandBus)
    {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        if (!($data instanceof ReserveBookRequest)) {
            throw new InvalidArgumentException();
        }

        $bookId = $uriVariables['uuid'];
        $clientId = '6229d52f-1fff-4092-ae12-3bf3e408e1c5';
        $this->commandBus->dispatch(new ReserveBookCommand(
            bookId: $bookId,
            clientId: $clientId,
            reserveFrom: $data->reserveFrom,
            reserveTo: $data->reserveTo,
        ));
    }
}