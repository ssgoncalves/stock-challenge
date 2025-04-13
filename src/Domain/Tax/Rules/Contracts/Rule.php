<?php

declare(strict_types=1);

namespace Stock\Domain\Tax\Rules\Contracts;

use Stock\Domain\Tax\TaxableOperation;

interface Rule
{
    public function supports(TaxableOperation $operation): bool;

    public function calculate(TaxableOperation $operation): float;
}
