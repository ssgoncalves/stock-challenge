<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Shared\Rules;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Stock\Domain\Shared\Rules\TaxExemptionLimit;

class TaxExemptionLimitTest extends TestCase
{
    #[DataProvider('getIsExemptionScenarios')]
    public function testShouldIsExemption(float $value, bool $expected): void
    {
        // Actions
        $result = TaxExemptionLimit::isExempt($value);

        // Assertions
        $this->assertSame($expected, $result);
    }

    public static function getIsExemptionScenarios(): array
    {
        return [
            'exemption' => [19_999.99, true],
            'not exemption' => [20_000.01, false],
            'exactly limit' => [20_000.00, true],
        ];
    }
}