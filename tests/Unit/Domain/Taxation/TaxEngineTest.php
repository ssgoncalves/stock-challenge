<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Taxation;

use Mockery;
use PHPUnit\Framework\TestCase;
use Stock\Domain\Shared\Enums\OperationType;
use Stock\Domain\Taxation\ProcessedOperation;
use Stock\Domain\Taxation\Rules\Contracts\Rule;
use Stock\Domain\Taxation\TaxEngine;

class TaxEngineTest extends TestCase
{
    public function testShouldCalculate(): void
    {
        // Set
        $sellRule = Mockery::mock(Rule::class);
        $calculator = new TaxEngine([$sellRule]);
        $profit = 1000.0;
        $expectedTax = 200.0;

        $taxableOperation = new ProcessedOperation(
            type: OperationType::SELL,
            profit: $profit,
        );

        // Expectations
        $sellRule->expects()
            ->calculate($taxableOperation)
            ->andReturn(200.0);

        // Actions
        $result = $calculator->calculate(operation: $taxableOperation);

        // Assertions
        $this->assertSame($expectedTax, $result);
    }
}