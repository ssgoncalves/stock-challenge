<?php

declare(strict_types=1);

namespace Stock\Tax\Domain\TaxCalculator\Rules;

use Stock\Tax\Domain\Enums\OperationType;
use Stock\Tax\Domain\TaxCalculator\Contracts\Rule;
use Stock\Tax\Domain\TaxCalculator\DTOs\InputDTO;

readonly class Sell implements Rule
{
    private const float MIN_PROFIT = 20000;

    private const float TAX_RATE = 0.20;

    public function __construct()
    {
    }

    public function getTax(InputDTO $inputDTO): float
    {

        if (!$this->hasTax($inputDTO)) {
            return 0.0;
        }

        return round(
            num: $inputDTO->taxableProfit * self::TAX_RATE,
            precision: 2,
            mode: PHP_ROUND_HALF_UP
        );
    }

    private function hasTax(InputDTO $inputDTO): bool
    {
        return $inputDTO->operationType === OperationType::SELL
            && $inputDTO->taxableProfit > self::MIN_PROFIT;
    }
}
