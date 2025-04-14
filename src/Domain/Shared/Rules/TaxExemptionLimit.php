<?php

declare(strict_types=1);

namespace Stock\Domain\Shared\Rules;

final class TaxExemptionLimit
{
    private const float LIMIT = 20_000.00;

    public static function isExempt(float $operationValue): bool
    {
        return $operationValue <= self::LIMIT;
    }

}
