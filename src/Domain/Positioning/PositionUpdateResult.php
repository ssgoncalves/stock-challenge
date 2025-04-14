<?php

declare(strict_types=1);

namespace Stock\Domain\Positioning;

readonly class PositionUpdateResult
{
    public function __construct(public Position $position, public float $compensatedProfit)
    {

    }
}