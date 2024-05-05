<?php

namespace App\Tests\Shared\Specification;

use App\Domain\Shared\Validation\Rule\RuleInterface;

class TestEvenRule implements RuleInterface
{
    public function getValidatorClass(): string
    {
        return TestEvenRuleValidator::class;
    }
}
