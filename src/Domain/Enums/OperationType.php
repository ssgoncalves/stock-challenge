<?php

declare(strict_types=1);

namespace Stock\Domain\Enums;

enum OperationType
{
    case SELL;

    case BUY;
}
