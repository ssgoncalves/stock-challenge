<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Positioning\Updating\Updaters;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Stock\Domain\Positioning\Position;
use Stock\Domain\Positioning\Updating\PositionUpdateResult;
use Stock\Domain\Positioning\Updating\Updaters\BuyUpdater;
use Stock\Domain\Shared\DTOs\Operation;
use Stock\Domain\Shared\Enums\OperationType;

class BuyUpdaterTest extends TestCase
{

    #[DataProvider('getUpdateScenarios')]
    public function testShouldUpdate(Position $position, Operation $operation, PositionUpdateResult $expected)
    {
        // Set
        $updater = new BuyUpdater();

        // Actions
        $result = $updater->update($position, $operation);

        // Assertions
        $this->assertEquals($expected, $result);
    }

    public static function getUpdateScenarios(): array
    {
        return [
            'Buy operation with no existing position' => [
                'position' => new Position(),
                'operation' => new Operation(type: OperationType::BUY, quantity: 10, price: 7.5),
                'expected' => new PositionUpdateResult(
                    position: new Position(quantity: 10, averagePrice: 7.5),
                    compensatedProfit: 0,
                    operationValue: 75,
                )
            ],
            'Buy operation with existing position' => [
                'position' => new Position(quantity: 5, averagePrice: 10.0),
                'operation' => new Operation(type: OperationType::BUY, quantity: 10, price: 7.5),
                'expected' => new PositionUpdateResult(
                    position: new Position(quantity: 15, averagePrice: 8.33),
                    compensatedProfit: 0,
                    operationValue: 75,
                ),
            ],
            'Buy operation with existing position and accumulated loss' => [
                'position' => new Position(quantity: 5, averagePrice: 10.0, accumulatedLoss: 100.0),
                'operation' => new Operation(type: OperationType::BUY, quantity: 10, price: 7.5),
                'expected' => new PositionUpdateResult(
                    position: new Position(quantity: 15, averagePrice: 8.33, accumulatedLoss: 100.0),
                    compensatedProfit: 0,
                    operationValue: 75,
                ),
            ],
        ];
    }

    #[DataProvider('getSupportsScenarios')]
    public function testShouldSupports(Operation $operation, bool $expected): void
    {
        // Set
        $updater = new BuyUpdater();

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
                'expected' => true,
            ],
            'Sell operation' => [
                'operation' => new Operation(type: OperationType::SELL, quantity: 10, price: 7.5),
                'expected' => false,
            ],
        ];
    }
}