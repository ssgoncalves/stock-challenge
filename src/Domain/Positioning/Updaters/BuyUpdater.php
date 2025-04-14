<?php

declare(strict_types=1);

namespace Stock\Domain\Positioning\Updaters;

use Stock\Domain\Positioning\Contracts\PositionUpdater;
use Stock\Domain\Positioning\Position;
use Stock\Domain\Positioning\PositionUpdateResult;
use Stock\Domain\Shared\DTOs\Operation;
use Stock\Domain\Shared\Enums\OperationType;

class BuyUpdater implements PositionUpdater
{
    public function supports(Operation $operation): bool
    {
        return $operation->type === OperationType::BUY;
    }

    public function update(Position $position, Operation $operation): PositionUpdateResult
    {
        $currentInvestment = $position->averagePrice * $position->quantity;
        $newInvestment = $operation->price * $operation->quantity;
        $totalInvestment = $currentInvestment + $newInvestment;
        $totalQuantity = $position->quantity + $operation->quantity;
        $newAveragePrice = $totalInvestment / $totalQuantity;

        $position =  new Position(
            quantity: $totalQuantity,
            averagePrice: round($newAveragePrice, 2, PHP_ROUND_HALF_UP),
            accumulatedLoss: $position->accumulatedLoss,
        );

        return new PositionUpdateResult(
            position: $position,
            compensatedProfit: 0,
        );
    }
}
