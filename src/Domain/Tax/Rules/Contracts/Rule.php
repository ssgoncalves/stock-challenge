<?php

declare(strict_types=1);

namespace Stock\Domain\Tax\Rules\Contracts;

use Stock\Domain\Tax\ProcessedOperation;

interface Rule
{
    public function supports(ProcessedOperation $operation): bool;

    public function calculate(ProcessedOperation $operation): float;
}
