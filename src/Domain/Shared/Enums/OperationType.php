<?php

declare(strict_types=1);

namespace Stock\Domain\Shared\Enums;

enum OperationType
{
    case SELL;

    case BUY;
}
