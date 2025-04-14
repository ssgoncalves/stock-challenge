<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Shared\Factories;

use PHPUnit\Framework\TestCase;
use Stock\Domain\Shared\DTOs\Operation;
use Stock\Domain\Shared\Enums\OperationType;
use Stock\Infrastructure\Shared\factory\OperationBatchFactory;

class OperationBatchFactoryTest extends TestCase
{
    public function testShouldCreateOperations(): void
    {
        $input = [
            ['operation' => 'buy', 'quantity' => 1000, 'unit-cost' => 10.0],
            ['operation' => 'sell', 'quantity' => 500, 'unit-cost' => 12.0],
        ];

        $result = OperationBatchFactory::createFromArray($input);

        $this->assertCount(2, $result);

        $this->assertInstanceOf(Operation::class, $result[0]);
        $this->assertSame(OperationType::BUY, $result[0]->type);
        $this->assertSame(1000, $result[0]->quantity);
        $this->assertSame(10.0, $result[0]->price);

        $this->assertInstanceOf(Operation::class, $result[1]);
        $this->assertSame(OperationType::SELL, $result[1]->type);
        $this->assertSame(500, $result[1]->quantity);
        $this->assertSame(12.0, $result[1]->price);
    }

    public function testShouldReturnEmptyArrayForEmptyInput(): void
    {
        $result = OperationBatchFactory::createFromArray([]);
        $this->assertSame([], $result);
    }
}
