<?php

declare(strict_types=1);

namespace Books\Infrastructure\Doctrine\Entity;

use Doctrine\ORM\Mapping as ORM;
use SharedKernel\Infrastructure\Doctrine\Entity\BookInterface;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\Table(name: 'book_clients')]
class Client implements BookInterface
{
    public function __construct(
        #[ORM\Id]
        #[ORM\Column(type: 'uuid')]
        private Uuid $uuid,
    ) {
    }

    public function getId(): Uuid
    {
        return $this->uuid;
    }
}