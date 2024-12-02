<?php

declare(strict_types=1);

namespace Books\Infrastructure\Api\Resource\Request;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class ReserveBookRequest
{
    public function __construct(
        #[Assert\NotBlank(allowNull: false)]
        public int $reserveFrom,
        #[Assert\NotBlank(allowNull: false)]
        public int $reserveTo,
    ) {
    }
}