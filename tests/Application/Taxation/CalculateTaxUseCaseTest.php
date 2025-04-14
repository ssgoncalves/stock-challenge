<?php

declare(strict_types=1);

namespace Tests\Application\Taxation;

use Mockery;
use PHPUnit\Framework\TestCase;
use Stock\Application\Taxation\CalculateTaxUseCase;
use Stock\Application\Taxation\DTOs\TaxCalculationResult;
use Stock\Domain\Positioning\Position;
use Stock\Domain\Positioning\Updating\PositionUpdateHandler;
use Stock\Domain\Positioning\Updating\PositionUpdateResult;
use Stock\Domain\Shared\DTOs\Operation;
use Stock\Domain\Shared\Enums\OperationType;
use Stock\Domain\Taxation\ProcessedOperation;
use Stock\Domain\Taxation\TaxEngine;

class CalculateTaxUseCaseTest extends TestCase
{
    public function testShouldExecute(): void
    {
        // Set
        $positionUpdateHandler = Mockery::mock(PositionUpdateHandler::class);
        $taxEngine = Mockery::mock(TaxEngine::class);
        $operation = new Operation(type: OperationType::SELL, quantity: 10, price: 100.0);
        $expected = new TaxCalculationResult(tax: 100.53);

        $useCase = new CalculateTaxUseCase(
            positionUpdateHandler: $positionUpdateHandler,
            taxEngine: $taxEngine,
        );

        $updateResult = new PositionUpdateResult(
            position: new Position(10, 100.0, 0.0),
            compensatedProfit: 0.0,
        );

        $operations = [$operation];

        // Expectation
        $positionUpdateHandler->expects()
            ->update(Mockery::type(Position::class), $operation)
            ->andReturn($updateResult);

        $taxEngine->expects()
            ->calculate(Mockery::type(ProcessedOperation::class))
            ->andReturn(100.53);

        // Actions
        $result = $useCase->execute($operations);

        // Assertions
        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertEquals($expected, $result[0]);
    }
}