<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Taxation;

use PHPUnit\Framework\TestCase;
use Stock\Application\Taxation\CalculateTaxUseCase;
use Stock\Infrastructure\Taxation\CalculateTaxUseCaseFactory;

class CalculateTaxUseCaseFactoryTest extends TestCase
{
    public function testShouldCreate(): void
    {
        // Actions
        $result = CalculateTaxUseCaseFactory::create();

        // Assertions
        $this->assertInstanceOf(CalculateTaxUseCase::class, $result);
    }
}