<?php

declare(strict_types=1);

namespace Stock\Domain\Taxation\Rules;

use Stock\Domain\Shared\Enums\OperationType;
use Stock\Domain\Taxation\ProcessedOperation;
use Stock\Domain\Taxation\Rules\Contracts\Rule;

readonly class Sell implements Rule
{
    private const float MAX_TAX_FREE_PROFIT = 20_000;

    private const float TAX_RATE = 0.20;

    public function __construct()
    {
    }

    public function calculate(ProcessedOperation $operation): float
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

    public function supports(ProcessedOperation $operation): bool
    {
        return $operation->type === OperationType::SELL
            && $operation->profit > self::MAX_TAX_FREE_PROFIT;
    }
}
