<?php

declare(strict_types=1);

namespace Stock\Infrastructure\Taxation;

use Stock\Application\Taxation\CalculateTaxUseCase;
use Stock\Domain\Positioning\Updating\PositionUpdaterHandler;
use Stock\Domain\Positioning\Updating\Updaters\BuyUpdater;
use Stock\Domain\Positioning\Updating\Updaters\SellUpdater;
use Stock\Domain\Taxation\Rules\Sell;
use Stock\Domain\Taxation\TaxEngine;

class CalculateTaxUseCaseFactory
{
    public static function create(): CalculateTaxUseCase
    {
        $sellUpdater = new SellUpdater();
        $buyUpdater = new BuyUpdater();

        $taxEngine = new TaxEngine(rules: [new Sell()]);

        $positionUpdateHandler = new PositionUpdaterHandler(
            updaters: [$sellUpdater, $buyUpdater],
        );

        return new CalculateTaxUseCase(
            positionUpdateHandler: $positionUpdateHandler,
            taxEngine: $taxEngine,
        );
    }
}
