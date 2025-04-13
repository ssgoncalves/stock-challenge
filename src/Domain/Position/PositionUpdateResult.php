<?php

declare(strict_types=1);

namespace Stock\Domain\Position;

use Stock\Domain\Entities\Position;

readonly class PositionUpdateResult
{
    public function __construct(public Position $position, public float $profit)
    {

    }

    public function hasProfit(): bool
    {
        return $this->profit > 0;
    }

}