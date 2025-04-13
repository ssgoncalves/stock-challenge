<?php

declare(strict_types=1);

namespace Stock\Domain\Position\Contracts;

use Stock\Domain\DTOs\Operation;
use Stock\Domain\Entities\Position;
use Stock\Domain\Position\PositionUpdateResult;

interface PositionUpdater
{
    public function supports(Operation $operation): bool;

    public function update(Position $position, Operation $operation): PositionUpdateResult;
}