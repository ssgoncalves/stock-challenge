<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Position\Updaters;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Stock\Domain\DTOs\Operation;
use Stock\Domain\Entities\Position;
use Stock\Domain\Enums\OperationType;
use Stock\Domain\Position\PositionUpdateResult;
use Stock\Domain\Position\Updaters\BuyUpdater;

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
                    profit: 0,
                )
            ],
            'Buy operation with existing position' => [
                'position' => new Position(quantity: 5, averagePrice: 10.0),
                'operation' => new Operation(type: OperationType::BUY, quantity: 10, price: 7.5),
                'expected' => new PositionUpdateResult(
                    position: new Position(quantity: 15, averagePrice: 8.33),
                    profit: 0,
                ),
            ],
            'Buy operation with existing position and accumulated loss' => [
                'position' => new Position(quantity: 5, averagePrice: 10.0, accumulatedLoss: 100.0),
                'operation' => new Operation(type: OperationType::BUY, quantity: 10, price: 7.5),
                'expected' => new PositionUpdateResult(
                    position: new Position(quantity: 15, averagePrice: 8.33, accumulatedLoss: 100.0),
                    profit: 0
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