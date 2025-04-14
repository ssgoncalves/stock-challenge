<?php

declare(strict_types=1);

namespace Stock\Domain\Positioning\Contracts;

use Stock\Domain\Positioning\Position;
use Stock\Domain\Positioning\Updating\PositionUpdateResult;
use Stock\Domain\Shared\DTOs\Operation;

interface PositionUpdater
{
    public function supports(Operation $operation): bool;

    public function update(Position $position, Operation $operation): PositionUpdateResult;
}