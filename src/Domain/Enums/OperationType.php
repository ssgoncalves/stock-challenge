<?php

declare(strict_types=1);

namespace Stock\Tax\Domain\Enums;

enum OperationType
{
    case SELL;

    case BUY;
}
