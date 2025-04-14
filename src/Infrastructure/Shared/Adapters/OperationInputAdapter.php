<?php

declare(strict_types=1);

namespace Stock\Infrastructure\Shared\Adapters;

use InvalidArgumentException;
use Stock\Domain\Shared\DTOs\Operation;
use Stock\Domain\Shared\Enums\OperationType;

class OperationInputAdapter
{
    public static function fromArray(array $data): Operation
    {

        if (!isset($data['operation'], $data['quantity'], $data['unit-cost'])) {
            throw new InvalidArgumentException('Missing required fields: operation, quantity, or unit-cost.');
        }

        if (!$type =  OperationType::tryFrom($data['operation'])){
            throw new InvalidArgumentException('Invalid operation type.');
        }

        return new Operation(
            type: $type,
            quantity: (int) $data['quantity'],
            price: (float) $data['unit-cost']
        );
    }
}