<?php

declare(strict_types=1);

namespace Auth\Infrastructure\Doctrine\Entity;

use Doctrine\ORM\Mapping as ORM;
use SharedKernel\Infrastructure\Doctrine\Entity\ClientInterface;

#[ORM\Entity]
#[ORM\Table(name: 'clients')]
class Client implements ClientInterface
{
    public function __construct(
        #[ORM\Id]
        #[ORM\Column(type: 'uuid')]
        private Uuid $uuid,
        #[ORM\Column(type: 'text', length: 255)]
        private string $name,
        #[ORM\Column(type: 'string', length: 255)]
        private string $email,
        #[ORM\Column(type: 'string', length: 255)]
        private string $password,
    ) {
    }
}