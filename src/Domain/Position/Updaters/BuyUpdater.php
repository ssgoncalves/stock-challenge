<?php

declare(strict_types=1);

namespace Stock\Domain\Position\Updaters;

use Stock\Domain\DTOs\Operation;
use Stock\Domain\Entities\Position;
use Stock\Domain\Enums\OperationType;
use Stock\Domain\Position\Contracts\PositionUpdater;
use Stock\Domain\Position\PositionUpdateResult;

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
