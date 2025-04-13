<?php

declare(strict_types=1);

namespace tests\Unit\Domain\TaxCalculator\Rules;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Stock\Tax\Domain\Enums\OperationType;
use Stock\Tax\Domain\TaxCalculator\DTOs\InputDTO;
use Stock\Tax\Domain\TaxCalculator\Rules\Sell;

class SellTest extends TestCase
{

    #[DataProvider('getTaxScenarios')]
    public function testShouldGetTax(InputDTO $inputDTO, float $expected): void
    {
        // Set
        $sell = new Sell();

        // Actions
        $result = $sell->getTax(inputDTO: $inputDTO);

        // Assertions
        $this->assertSame($expected, $result);
    }

    public static function getTaxScenarios(): array
    {
        return [
            'Sell operation with taxable profit greater than 20000' => [
                'inputDTO' => new InputDTO(
                    operationType: OperationType::SELL,
                    taxableProfit: 20001.0,
                ),
                'expected' => 4000.20,
            ],
            'Sell operation with taxable profit less than 20000' => [
                'inputDTO' => new InputDTO(
                    operationType: OperationType::SELL,
                    taxableProfit: 19999.0,
                ),
                'expected' => 0.0,
            ],
            'Buy operation with taxable profit greater than 20000' => [
                'inputDTO' => new InputDTO(
                    operationType: OperationType::BUY,
                    taxableProfit: 20001.0,
                ),
                'expected' => 0.0,
            ],
            'Buy operation with taxable profit less than 20000' => [
                'inputDTO' => new InputDTO(
                    operationType: OperationType::BUY,
                    taxableProfit: 19999.0,
                ),
                'expected' => 0.0,
            ],
            'Sell operation with taxable profit equal to 20000' => [
                'inputDTO' => new InputDTO(
                    operationType: OperationType::SELL,
                    taxableProfit: 20000.0,
                ),
                'expected' => 0.0,
            ],
            'Buy operation with taxable profit equal to 20000' => [
                'inputDTO' => new InputDTO(
                    operationType: OperationType::BUY,
                    taxableProfit: 20000.0,
                ),
                'expected' => 0.0,
            ],
            'Sell operation with taxable profit equal to 0' => [
                'inputDTO' => new InputDTO(
                    operationType: OperationType::SELL,
                    taxableProfit: 0.0,
                ),
                'expected' => 0.0,
            ],
            'Buy operation with taxable profit equal to 0' => [
                'inputDTO' => new InputDTO(
                    operationType: OperationType::BUY,
                    taxableProfit: 0.0,
                ),
                'expected' => 0.0,
            ],
        ];
    }
}
