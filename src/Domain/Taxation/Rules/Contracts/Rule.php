<?php

declare(strict_types=1);

namespace Stock\Domain\Taxation\Rules\Contracts;

use Stock\Domain\Taxation\ProcessedOperation;

interface Rule
{
    public function supports(ProcessedOperation $operation): bool;

    public function calculate(ProcessedOperation $operation): float;
}
