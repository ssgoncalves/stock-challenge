<?php

declare(strict_types=1);

namespace Stock\Tax\Domain\TaxCalculator\DTOs;


use Stock\Tax\Domain\Enums\OperationType;

class InputDTO
{
    public function __construct(
        public OperationType $operationType,
        public float         $taxableProfit,
    )
    {
    }

}