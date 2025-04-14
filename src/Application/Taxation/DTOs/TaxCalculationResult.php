<?php

declare(strict_types=1);

namespace Stock\Application\Taxation\DTOs;

readonly class TaxCalculationResult
{
    public function __construct(public float $tax)
    {
    }

}