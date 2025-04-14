<?php

declare(strict_types=1);

namespace Stock\Domain\Positioning\Updating\Updaters;

use Stock\Domain\Positioning\Contracts\PositionUpdater;
use Stock\Domain\Positioning\Position;
use Stock\Domain\Positioning\Updating\PositionUpdateResult;
use Stock\Domain\Shared\DTOs\Operation;
use Stock\Domain\Shared\Enums\OperationType;

class SellUpdater implements PositionUpdater
{
    public function supports(Operation $operation): bool
    {
        return $operation->type === OperationType::SELL;
    }

    public function update(Position $position, Operation $operation): PositionUpdateResult
    {
        $loss = 0.0;
        $profit = 0.0;
        $newQuantity = $position->quantity - $operation->quantity;
        $buyCost = $position->averagePrice * $operation->quantity;
        $sellValue = $operation->price * $operation->quantity;

        $saleBalance = $sellValue - $buyCost;

        $saleBalance < 0
            ? $loss = abs($saleBalance)
            : $profit = $saleBalance;

        $compensatedLoss = max(0, $position->accumulatedLoss - $profit) + $loss;
        $compensatedProfit = max(0, $profit - $position->accumulatedLoss);

        $averagePrice = $newQuantity < 1
            ? 0
            : $position->averagePrice;

        $position = new Position(
            quantity: $newQuantity,
            averagePrice: $averagePrice,
            accumulatedLoss: $compensatedLoss,
        );

        return new PositionUpdateResult(
            position: $position,
            compensatedProfit: $compensatedProfit,
        );
    }

}
