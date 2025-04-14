<?php

require __DIR__ . '/../vendor/autoload.php';

use Stock\Domain\Shared\DTOs\Operation;
use Stock\Infrastructure\Taxation\CalculateTaxUseCaseFactory;

$calculateTax = CalculateTaxUseCaseFactory::create();

$operationsJson = '[{"operation":"buy", "unit-cost":10.00, "quantity": 100},
{"operation":"sell", "unit-cost":15.00, "quantity": 50},
{"operation":"sell", "unit-cost":15.00, "quantity": 50}]';

$operationsJson = '[{"operation":"buy", "unit-cost":10.00, "quantity": 10000},
{"operation":"sell", "unit-cost":20.00, "quantity": 5000},
{"operation":"sell", "unit-cost":5.00, "quantity": 5000}]';

$operationsJson = '[{"operation":"buy", "unit-cost":10.00, "quantity": 10000},
{"operation":"sell", "unit-cost":5.00, "quantity": 5000},
{"operation":"sell", "unit-cost":20.00, "quantity": 3000}]';

$operationsJson = '[{"operation":"buy", "unit-cost":10.00, "quantity": 10000},
{"operation":"buy", "unit-cost":25.00, "quantity": 5000},
{"operation":"sell", "unit-cost":15.00, "quantity": 10000}]';

$operationsJson = '[{"operation":"buy", "unit-cost":10.00, "quantity": 10000},
{"operation":"buy", "unit-cost":25.00, "quantity": 5000},
{"operation":"sell", "unit-cost":15.00, "quantity": 10000},
{"operation":"sell", "unit-cost":25.00, "quantity": 5000}]';


$operations = json_decode($operationsJson, true);

$list = [];

foreach ($operations as $operation) {
    $list[] = new Operation(
        type: $operation['operation'] === 'sell'
            ? Stock\Domain\Shared\Enums\OperationType::SELL
            : Stock\Domain\Shared\Enums\OperationType::BUY,
        quantity: $operation['quantity'],
        price: $operation['unit-cost'],
    );
}


$result = $calculateTax->execute($list);

var_dump(json_encode($result));
