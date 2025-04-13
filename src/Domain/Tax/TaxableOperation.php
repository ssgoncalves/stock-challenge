<?php

declare(strict_types=1);

namespace Stock\Domain\Tax;


use Stock\Domain\Enums\OperationType;

class TaxableOperation
{
    public function __construct(
        public OperationType $type,
        public float         $profit,
    )
    {
    }

}