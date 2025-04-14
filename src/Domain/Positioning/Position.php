<?php

declare(strict_types=1);

namespace Stock\Domain\Positioning;

readonly class Position
{
    public function __construct(
        public int $quantity = 0,
        public float $averagePrice = 0.0,
        public float $accumulatedLoss = 0.0,
    )
    {
    }

}