<?php

declare(strict_types=1);

namespace Books\Infrastructure\Api\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Books\Application\CQRS\Command\ChangeBookDescriptionCommand;
use Books\Infrastructure\Api\Resource\Request\ChangeBookDescriptionRequest;
use Symfony\Component\Messenger\MessageBusInterface;
use UnexpectedValueException;

final class ChangeBookDescriptionProcessor implements ProcessorInterface
{
    public function __construct(private readonly MessageBusInterface $commandBus)
    {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        if (!($data instanceof ChangeBookDescriptionRequest)) {
            throw new UnexpectedValueException();
        }

        $this->commandBus->dispatch(new ChangeBookDescriptionCommand(
            $uriVariables['uuid'],
            $data->newDescription,
        ));
    }
}