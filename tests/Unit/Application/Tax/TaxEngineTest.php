<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Tax;

use Mockery;
use PHPUnit\Framework\TestCase;
use Stock\Application\Tax\TaxEngine;
use Stock\Domain\Enums\OperationType;
use Stock\Domain\Tax\TaxableOperation;
use Stock\Domain\Tax\Rules\Contracts\Rule;

class TaxEngineTest extends TestCase
{
    public function testShouldCalculate(): void
    {
        // Set
        $sellRule = Mockery::mock(Rule::class);
        $calculator = new TaxEngine([$sellRule]);
        $profit = 1000.0;
        $expectedTax = 200.0;

        $taxableOperation = new TaxableOperation(
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