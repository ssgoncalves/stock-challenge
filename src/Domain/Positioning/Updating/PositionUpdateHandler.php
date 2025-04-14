<?php

declare(strict_types=1);

namespace Stock\Domain\Positioning\Updating;

use RuntimeException;
use Stock\Domain\Positioning\Contracts\PositionUpdater;
use Stock\Domain\Positioning\Position;
use Stock\Domain\Shared\DTOs\Operation;

class PositionUpdateHandler
{
    /**
     * @param array<0, PositionUpdater> $updaters
     */
    public function __construct(private array $updaters)
    {
    }


    public function update(Position $position, Operation $operation): PositionUpdateResult
    {
        foreach ($this->updaters as $updater){
            if ($updater->supports(operation: $operation)) {
                return $updater->update(
                    position: $position,
                    operation: $operation,
                );
            }
        }

        throw new RuntimeException('No suitable updater found for the operation.');
    }
}