<?php

declare(strict_types=1);

namespace Books\Infrastructure\Api\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Books\Application\CQRS\Command\MakeBookUnavailableCommand;
use Symfony\Component\Messenger\MessageBusInterface;

final readonly class MakeBookUnavailableProcessor implements ProcessorInterface
{
    public function __construct(private MessageBusInterface $commandBus)
    {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        $bookId = $uriVariables['uuid'];
        $this->commandBus->dispatch(new MakeBookUnavailableCommand($bookId));
    }
}