<?php

declare(strict_types=1);

namespace Stock\Application\Tax;

use Stock\Domain\Tax\ProcessedOperation;
use Stock\Domain\Tax\Rules\Contracts\Rule;

readonly class TaxEngine
{
    /**
     * @param array<0, Rule> $rules
     */
    public function __construct(private array $rules)
    {
    }

    /**
     * Processes a given financial operation by evaluating all applicable tax rules.
     *
     * The default behavior is to sum the tax returned by each rule that applies to the operation.
     * However, this strategy can be changed — for example, to apply only the first matching rule,
     * use the maximum value, or any other aggregation logic.
     *
     * @param ProcessedOperation $operation The input representing a single financial operation.
     * @return float The total tax calculated from all applicable rules.
     */
    public function calculate(ProcessedOperation $operation): float
    {
        return array_reduce(
            array: $this->rules,
            callback: fn(float $carry, Rule $rule): float => $carry + $rule->calculate(operation: $operation),
            initial: 0.0
        );
    }
}