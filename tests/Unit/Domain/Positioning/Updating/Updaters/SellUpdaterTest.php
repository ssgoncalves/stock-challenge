<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Positioning\Updating\Updaters;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Stock\Domain\Positioning\Position;
use Stock\Domain\Positioning\Updating\PositionUpdateResult;
use Stock\Domain\Positioning\Updating\Updaters\SellUpdater;
use Stock\Domain\Shared\DTOs\Operation;
use Stock\Domain\Shared\Enums\OperationType;

class SellUpdaterTest extends TestCase
{

    #[DataProvider('getUpdateScenarios')]
    public function testShouldUpdate(Position $position, Operation $operation, PositionUpdateResult $expected)
    {
        // Set
        $updater = new SellUpdater();

        // Actions
        $result = $updater->update($position, $operation);

        // Assertions
        $this->assertEquals($expected, $result);
    }

    public static function getUpdateScenarios(): array
    {
        return [
            'Sell operation with profit' => [
                'position' => new Position(quantity: 10, averagePrice: 7.5, accumulatedLoss: 0),
                'operation' => new Operation(type: OperationType::SELL, quantity: 5, price: 10),
                'expected' => new PositionUpdateResult(
                    position: new Position(quantity: 5, averagePrice: 7.5, accumulatedLoss: 0),
                    compensatedProfit: 12.5,
                    operationValue: 50,
                ),
            ],
            'Sell operation with loss' => [
                'position' => new Position(quantity: 10, averagePrice: 7.5, accumulatedLoss: 0),
                'operation' => new Operation(type: OperationType::SELL, quantity: 5, price: 5),
                'expected' => new PositionUpdateResult(
                    position: new Position(quantity: 5, averagePrice: 7.5, accumulatedLoss: 12.5),
                    compensatedProfit: 0,
                    operationValue: 25,
                ),
            ],
            'Sell operation with no profit or loss' => [
                'position' => new Position(quantity: 10, averagePrice: 7.5, accumulatedLoss: 0),
                'operation' => new Operation(type: OperationType::SELL, quantity: 5, price: 7.5),
                'expected' => new PositionUpdateResult(
                    position: new Position(quantity: 5, averagePrice: 7.5, accumulatedLoss: 0),
                    compensatedProfit: 0,
                    operationValue: 37.5,
                ),
            ],
            'Sell all shares' => [
                'position' => new Position(quantity: 10, averagePrice: 7.5, accumulatedLoss: 0),
                'operation' => new Operation(type: OperationType::SELL, quantity: 10, price: 10),
                'expected' => new PositionUpdateResult(
                    position: new Position(quantity: 0, averagePrice: 0, accumulatedLoss: 0),
                    compensatedProfit: 25,
                    operationValue: 100,
                ),
            ],
            'Sell operation with accumulated loss' => [
                'position' => new Position(quantity: 10, averagePrice: 7.5, accumulatedLoss: 10),
                'operation' => new Operation(type: OperationType::SELL, quantity: 5, price: 5),
                'expected' => new PositionUpdateResult(
                    position: new Position(quantity: 5, averagePrice: 7.5, accumulatedLoss: 22.5),
                    compensatedProfit: 0,
                    operationValue: 25,
                ),
            ],
            'Sell operation with compensated loss' => [
                'position' => new Position(quantity: 1000, averagePrice: 7.5, accumulatedLoss: 10),
                'operation' => new Operation(type: OperationType::SELL, quantity: 1000, price: 25),
                'expected' => new PositionUpdateResult(
                    position: new Position(quantity: 0, averagePrice: 0, accumulatedLoss: 0),
                    compensatedProfit: 17_490.0,
                    operationValue: 25_000.0,
                ),
            ],
            'Sell operation with profit below exemption limit should not compensate loss' => [
                'position' => new Position(quantity: 10, averagePrice: 7.5, accumulatedLoss: 10),
                'operation' => new Operation(type: OperationType::SELL, quantity: 5, price: 10),
                'expected' => new PositionUpdateResult(
                    position: new Position(quantity: 5, averagePrice: 7.5, accumulatedLoss: 10),
                    compensatedProfit: 12.5,
                    operationValue: 50,
                ),
            ],
        ];
    }

    #[DataProvider('getSupportsScenarios')]
    public function testShouldSupports(Operation $operation, bool $expected): void
    {
        // Set
        $updater = new SellUpdater();

        // Actions
        $result = $updater->supports($operation);

        // Assertions
        $this->assertSame($expected, $result);
    }

    public static function getSupportsScenarios(): array
    {
        return [
            'Buy operation' => [
                'operation' => new Operation(type: OperationType::BUY, quantity: 10, price: 7.5),
                'expected' => false,
            ],
            'Sell operation' => [
                'operation' => new Operation(type: OperationType::SELL, quantity: 10, price: 7.5),
                'expected' => true,
            ],
        ];
    }
}