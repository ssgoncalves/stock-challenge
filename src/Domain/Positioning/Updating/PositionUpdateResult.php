<?php

declare(strict_types=1);

namespace Stock\Domain\Positioning\Updating;

use Stock\Domain\Positioning\Position;

readonly class PositionUpdateResult
{
    public function __construct(
        public Position $position,
        public float $compensatedProfit,
        public float $operationValue,
    )
    {

    }
}