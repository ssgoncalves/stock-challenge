<?php

declare(strict_types=1);

namespace Stock\Application\Taxation;

use Stock\Application\Taxation\DTOs\TaxCalculationResult;
use Stock\Domain\Positioning\Position;
use Stock\Domain\Positioning\Updating\PositionUpdateHandler;
use Stock\Domain\Shared\DTOs\Operation;
use Stock\Domain\Taxation\ProcessedOperation;
use Stock\Domain\Taxation\TaxEngine;

readonly class CalculateTaxUseCase
{
    public function __construct(
        private PositionUpdateHandler $positionUpdateHandler,
        private TaxEngine $taxEngine,
    )
    {
    }

    /**
     * @param array<0, Operation> $operations
     */
    public function execute(array $operations): array
    {
        $position = new Position();

        $taxes = [];

        foreach ($operations as $operation) {

            $positionUpdateResult = $this->positionUpdateHandler->update($position, $operation);
            $position = $positionUpdateResult->position;

            $processedOperation = new ProcessedOperation(
                type: $operation->type,
                profit: $positionUpdateResult->compensatedProfit,
                operationValue: $positionUpdateResult->operationValue,
            );

            $tax = $this->taxEngine->calculate(operation: $processedOperation);

            $taxes[] = new TaxCalculationResult(tax: $tax);
        }

        return $taxes;
    }
}
