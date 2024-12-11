<?php

declare(strict_types=1);

namespace Books\Infrastructure\Api\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Books\Application\CQRS\Command\CreateBookCommand;
use Books\Infrastructure\Api\Resource\Request\CreateBookRequest;
use Symfony\Component\Messenger\MessageBusInterface;
use UnexpectedValueException;

final readonly class CreateBookProcessor implements ProcessorInterface
{
    public function __construct(private MessageBusInterface $commandBus)
    {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        if (!($data instanceof CreateBookRequest)) {
            throw new UnexpectedValueException();
        }

        $this->commandBus->dispatch(new CreateBookCommand(
            name: $data->name,
            authorFirstName: $data->author->firstName,
            authorLastName: $data->author->lastName,
            description: $data->description,
            totalQuantity: $data->totalQuantity,
        ));
    }
}