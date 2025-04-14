<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Positioning\Updating;

use Mockery;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Stock\Domain\Positioning\Position;
use Stock\Domain\Positioning\Updating\PositionUpdaterHandler;
use Stock\Domain\Positioning\Updating\PositionUpdateResult;
use Stock\Domain\Positioning\Updating\Updaters\SellUpdater;
use Stock\Domain\Shared\DTOs\Operation;
use Stock\Domain\Shared\Enums\OperationType;

class PositionUpdaterHandlerTest extends TestCase
{

    public function testShouldUpdate(): void
    {
        // Set
        $sellUpdater = Mockery::mock(SellUpdater::class);
        $handler = new PositionUpdaterHandler([$sellUpdater]);
        $position = new Position();
        $operation = new Operation(
            type: OperationType::SELL,
            quantity: 10,
            price: 100.0,
        );

        // Expectation
        $sellUpdater->expects()
            ->supports($operation)
            ->once()
            ->andReturnTrue();

        $sellUpdater->expects()
            ->update($position, $operation)
            ->once()
            ->andReturn(new PositionUpdateResult(
                position: new Position(10, 100.0, 0.0),
                compensatedProfit: 0.0,
                operationValue: 1000.0,
            ));

        // Actions
        $result = $handler->update($position, $operation);

        // Assertions
        $this->assertInstanceOf(PositionUpdateResult::class, $result);
    }

    public function testShouldUpdateOnlyByCorrectUpdater(): void
    {
        // Set
        $buyUpdater = Mockery::mock(SellUpdater::class);
        $sellUpdater = Mockery::mock(SellUpdater::class);
        $handler = new PositionUpdaterHandler([$buyUpdater, $sellUpdater]);
        $position = new Position();
        $operation = new Operation(
            type: OperationType::SELL,
            quantity: 10,
            price: 100.0,
        );

        // Expectation
        $buyUpdater->expects()
            ->supports($operation)
            ->once()
            ->andReturnFalse();

        $sellUpdater->expects()
            ->supports($operation)
            ->once()
            ->andReturnTrue();

        $sellUpdater->expects()
            ->update($position, $operation)
            ->once()
            ->andReturn(new PositionUpdateResult(
                position: new Position(10, 100.0, 0.0),
                compensatedProfit: 0.0,
                operationValue: 1000.0,
            ));

        // Actions
        $result = $handler->update($position, $operation);

        // Assertions
        $this->assertInstanceOf(PositionUpdateResult::class, $result);
    }

    public function testShouldReturnExceptionWhenNoUpdaterFound(): void
    {
        // Set
        $handler = new PositionUpdaterHandler([]);
        $position = new Position();
        $operation = new Operation(
            type: OperationType::SELL,
            quantity: 10,
            price: 100.0,
        );

        // Assertions
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('No suitable updater found for the operation.');
        $handler->update($position, $operation);
    }
    
}