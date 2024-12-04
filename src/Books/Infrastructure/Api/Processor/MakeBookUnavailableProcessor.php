<?php

declare(strict_types=1);

namespace Books\Infrastructure\Api\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Books\Application\CQRS\Command\MakeBookUnavailableCommand;
use Books\Infrastructure\Api\Resource\Request\MakeBookUnavailableRequest;
use Symfony\Component\Messenger\MessageBusInterface;
use UnexpectedValueException;

final readonly class MakeBookUnavailableProcessor implements ProcessorInterface
{
    public function __construct(private MessageBusInterface $commandBus)
    {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        if (!($data instanceof MakeBookUnavailableRequest)) {
            throw new UnexpectedValueException();
        }

        $bookId = $uriVariables['uuid'];
        $this->commandBus->dispatch(new MakeBookUnavailableCommand($bookId));
    }
}