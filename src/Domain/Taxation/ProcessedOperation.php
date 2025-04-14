<?php

declare(strict_types=1);

namespace Stock\Domain\Taxation;


use Stock\Domain\Shared\Enums\OperationType;

class ProcessedOperation
{
    public function __construct(
        public OperationType $type,
        public float         $profit,
        public float         $operationValue,
    )
    {
    }

}