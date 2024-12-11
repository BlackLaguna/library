<?php

declare(strict_types=1);

namespace Books\Infrastructure\Doctrine\Entity;

use Doctrine\ORM\Mapping as ORM;
use SharedKernel\Infrastructure\Doctrine\Entity\BookInterface;

#[ORM\Entity]
#[ORM\Table(name: 'book_clients')]
class Client implements BookInterface
{
    public function __construct(
        #[ORM\Id]
        #[ORM\Column(type: 'text')]
        private string $email,
    ) {
    }

    public function getId(): string
    {
        return $this->email;
    }
}