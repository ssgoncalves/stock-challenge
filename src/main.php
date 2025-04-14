<?php

require __DIR__ . '/../vendor/autoload.php';

use Stock\Infrastructure\Shared\Adapters\StdinInputReader;
use Stock\Infrastructure\Shared\factories\OperationBatchFactory;
use Stock\Infrastructure\Taxation\CalculateTaxUseCaseFactory;

$calculateTax = CalculateTaxUseCaseFactory::create();
$inputs = StdinInputReader::read();

foreach ($inputs as $input) {
    $operations = OperationBatchFactory::createFromArray($input);
    $result = $calculateTax->execute($operations);
    echo json_encode($result) . PHP_EOL;
}
