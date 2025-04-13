<?php

declare(strict_types=1);

namespace Stock\Domain\DTOs;

use Stock\Domain\Enums\OperationType;

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