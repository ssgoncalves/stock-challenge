<?php

declare(strict_types=1);

namespace Stock\Tax\Domain\TaxCalculator\Contracts;

use Stock\Tax\Domain\TaxCalculator\DTOs\InputDTO;

interface Rule
{
    public function getTax(InputDTO $inputDTO): float;
}
