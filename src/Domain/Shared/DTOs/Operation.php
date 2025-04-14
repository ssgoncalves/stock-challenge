<?php

declare(strict_types=1);

namespace Stock\Domain\Shared\DTOs;

use Stock\Domain\Shared\Enums\OperationType;

readonly class Operation
{
    public function __construct(
        public OperationType $type,
        public int $quantity,
        public float $price,
    )
    {
    }

}