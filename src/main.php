<?php

use Stock\Application\Taxation\CalculateTaxUseCase;
use Stock\Domain\Positioning\Updating\PositionUpdateHandler;
use Stock\Domain\Positioning\Updating\Updaters\SellUpdater;
use Stock\Domain\Taxation\Rules\Sell;
use Stock\Domain\Taxation\TaxEngine;

$sellUpdater = new SellUpdater();
$buyUpdater = new SellUpdater();

$taxEngine = new TaxEngine(rules: [new Sell()]);

$positionUpdateHandler = new PositionUpdateHandler(
    updaters: [$sellUpdater, $buyUpdater],
);

$calculateTax = new CalculateTaxUseCase(
    positionUpdateHandler: $positionUpdateHandler,
    taxEngine: $taxEngine,
);

$operation1 = new Stock\Domain\Shared\DTOs\Operation(
    type: Stock\Domain\Shared\Enums\OperationType::BUY,
    quantity: 10_000,
    price: 10.0,
);

$operation2 = new Stock\Domain\Shared\DTOs\Operation(
    type: Stock\Domain\Shared\Enums\OperationType::SELL,
    quantity: 5_000,
    price: 2.0,
);

$operation3 = new Stock\Domain\Shared\DTOs\Operation(
    type: Stock\Domain\Shared\Enums\OperationType::SELL,
    quantity: 2_000,
    price: 20.0,
);

$operation4 = new Stock\Domain\Shared\DTOs\Operation(
    type: Stock\Domain\Shared\Enums\OperationType::SELL,
    quantity: 2_000,
    price: 20.0,
);

$operation5 = new Stock\Domain\Shared\DTOs\Operation(
    type: Stock\Domain\Shared\Enums\OperationType::SELL,
    quantity: 1_000,
    price: 25.0,
);

$result = $calculateTax->execute([
    $operation1,
    $operation2,
    $operation3,
    $operation4,
    $operation5,
]);

var_dump($result);
