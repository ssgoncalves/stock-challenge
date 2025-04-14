<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Shared\Adapters;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Stock\Domain\Shared\DTOs\Operation;
use Stock\Domain\Shared\Enums\OperationType;
use Stock\Infrastructure\Shared\Adapters\OperationInputAdapter;

class OperationInputAdapterTest extends TestCase
{
    #[DataProvider('getCreateFromArrayScenarios')]
    public function testShouldCreateOperationFromArray(array $data, Operation $expected): void
    {
        // Actions
        $operation = OperationInputAdapter::fromArray($data);

        // Assertions
        $this->assertEquals($expected, $operation);
    }

    public static function getCreateFromArrayScenarios(): array
    {
        return [
            'buy' => [
                'data' => [
                    'operation' => 'buy',
                    'quantity' => 10,
                    'unit-cost' => 100.0,
                ],
                'expected' => new Operation(
                    type: OperationType::BUY,
                    quantity: 10,
                    price: 100.0,
                ),
            ],
            'sell' => [
                'data' => [
                    'operation' => 'sell',
                    'quantity' => 5,
                    'unit-cost' => 50.0,
                ],
                'expected' => new Operation(
                    type: OperationType::SELL,
                    quantity: 5,
                    price: 50.0,
                ),
            ],
        ];
    }

    #[DataProvider('getInputsMissingRequiredFieldsScenarios')]
    public function testThrowsExceptionWhenInputIsMissingRequiredFields(array $data, string $expectedMessage)
    {
       // Expectations
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage($expectedMessage);

        // Actions
        OperationInputAdapter::fromArray($data);
    }

    public static function getInputsMissingRequiredFieldsScenarios(): array
    {
        return [
            'missing operation' => [
                'data' => [
                    'quantity' => 10,
                    'unit-cost' => 100.0,
                ],
                'expectedMessage' => 'Missing required fields: operation, quantity, or unit-cost.',
            ],
            'missing quantity' => [
                'data' => [
                    'operation' => 'buy',
                    'unit-cost' => 100.0,
                ],
                'expectedMessage' => 'Missing required fields: operation, quantity, or unit-cost.',
            ],
            'missing unit-cost' => [
                'data' => [
                    'operation' => 'buy',
                    'quantity' => 10,
                ],
                'expectedMessage' => 'Missing required fields: operation, quantity, or unit-cost.',
            ],
            'missing all fields' => [
                'data' => [],
                'expectedMessage' => 'Missing required fields: operation, quantity, or unit-cost.',
            ],
            'Invalid operation' => [
                'data' => [
                    'operation' => 'invalid',
                    'quantity' => 10,
                    'unit-cost' => 100.0,
                ],
                'expectedMessage' => 'Invalid operation type.',
            ]
        ];
    }
}