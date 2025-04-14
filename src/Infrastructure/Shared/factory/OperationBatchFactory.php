<?php

declare(strict_types=1);

namespace Stock\Infrastructure\Shared\factory;

use Stock\Domain\Shared\DTOs\Operation;
use Stock\Infrastructure\Shared\Adapters\OperationInputAdapter;

class OperationBatchFactory
{
    /**
     * @param array<int, array<string, mixed>> $input
     * @return Operation[]
     */
    public static function createFromArray(array $input): array
    {
        return array_map([OperationInputAdapter::class, 'fromArray'], $input);
    }
}