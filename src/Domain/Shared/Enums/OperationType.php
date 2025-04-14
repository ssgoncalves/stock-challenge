<?php

declare(strict_types=1);

namespace Stock\Domain\Shared\Enums;

enum OperationType: string
{
    case SELL = 'sell';

    case BUY = 'buy';
}
