<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Taxation\Rules;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Stock\Domain\Shared\Enums\OperationType;
use Stock\Domain\Taxation\ProcessedOperation;
use Stock\Domain\Taxation\Rules\Sell;

class SellTest extends TestCase
{

    #[DataProvider('getTaxScenarios')]
    public function testShouldGetTax(ProcessedOperation $operation, float $expected): void
    {
        // Set
        $sell = new Sell();

        // Actions
        $result = $sell->calculate(operation: $operation);

        // Assertions
        $this->assertSame($expected, $result);
    }

    public static function getTaxScenarios(): array
    {
        return [
            'Sell operation with taxable profit greater than 20000' => [
                'operation' => new ProcessedOperation(
                    type: OperationType::SELL,
                    profit: 20001.0,
                ),
                'expected' => 4000.20,
            ],
            'Sell operation with taxable profit less than 20000' => [
                'operation' => new ProcessedOperation(
                    type: OperationType::SELL,
                    profit: 19999.0,
                ),
                'expected' => 0.0,
            ],
            'Buy operation with taxable profit greater than 20000' => [
                'operation' => new ProcessedOperation(
                    type: OperationType::BUY,
                    profit: 20001.0,
                ),
                'expected' => 0.0,
            ],
            'Buy operation with taxable profit less than 20000' => [
                'operation' => new ProcessedOperation(
                    type: OperationType::BUY,
                    profit: 19999.0,
                ),
                'expected' => 0.0,
            ],
            'Sell operation with taxable profit equal to 20000' => [
                'operation' => new ProcessedOperation(
                    type: OperationType::SELL,
                    profit: 20000.0,
                ),
                'expected' => 0.0,
            ],
            'Buy operation with taxable profit equal to 20000' => [
                'operation' => new ProcessedOperation(
                    type: OperationType::BUY,
                    profit: 20000.0,
                ),
                'expected' => 0.0,
            ],
            'Sell operation with taxable profit equal to 0' => [
                'operation' => new ProcessedOperation(
                    type: OperationType::SELL,
                    profit: 0.0,
                ),
                'expected' => 0.0,
            ],
            'Buy operation with taxable profit equal to 0' => [
                'operation' => new ProcessedOperation(
                    type: OperationType::BUY,
                    profit: 0.0,
                ),
                'expected' => 0.0,
            ],
        ];
    }
}
