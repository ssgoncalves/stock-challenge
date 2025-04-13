<?php

declare(strict_types=1);

namespace Stock\Domain\Tax\Rules;

use Stock\Domain\Enums\OperationType;
use Stock\Domain\Tax\TaxableOperation;
use Stock\Domain\Tax\Rules\Contracts\Rule;

readonly class Sell implements Rule
{
    private const float MAX_TAX_FREE_PROFIT = 20_000;

    private const float TAX_RATE = 0.20;

    public function __construct()
    {
    }

    public function calculate(TaxableOperation $operation): float
    {
        if (!$this->supports($operation)) {
            return 0.0;
        }

        return round(
            num: $operation->profit * self::TAX_RATE,
            precision: 2,
            mode: PHP_ROUND_HALF_UP
        );
    }

    public function supports(TaxableOperation $operation): bool
    {
        return $operation->type === OperationType::SELL
            && $operation->profit > self::MAX_TAX_FREE_PROFIT;
    }
}
