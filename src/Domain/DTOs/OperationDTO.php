<?php

declare(strict_types=1);

namespace Stock\Tax\Domain\DTOs;

use Stock\Tax\Domain\Enums\OperationType;

readonly class OperationDTO
{
    public function __construct(public OperationType $type)
    {
    }

}